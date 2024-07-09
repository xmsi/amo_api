<?php

namespace App\Services\AmoCrm;

use AmoCRM\Collections\CustomFields\CustomFieldsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Exceptions\AmoCRMApiNoContentException;
use AmoCRM\Filters\ContactsFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\BaseCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\NumericCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\NumericCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\NumericCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\TextCustomFieldValueModel;
use Illuminate\Http\RedirectResponse;

class Contacts
{
    private ?ContactModel $contact;
    public bool $isCustomerCreated = false;

    public function __construct(private array $validated)
    {
        if (!$this->haveDouble()) {
            $this->add();
        }
    }

    public function getContact(): ContactModel
    {
        return $this->contact;
    }

    private function haveDouble(): bool
    {
        $filter = new ContactsFilter();
        $filter->setQuery($this->validated["phone"]);
        try {
            $contactsCollection = ApiClient::get()->contacts()->get($filter, [EntityTypesInterface::LEADS]);
            $contactsCollection->getBy(
                'customFieldsValues',
                (new MultitextCustomFieldValuesModel())
                    ->setFieldCode("PHONE")
                    ->setValues(
                        (new MultitextCustomFieldValueCollection())
                            ->add((new MultitextCustomFieldValueModel())
                                ->setEnum('WORK')
                                ->setValue($this->validated["phone"]))
                    )
            );

            if ($contactsCollection->count() > 0) {
                foreach ($contactsCollection as $contact) {
                    if (empty($contact->getLeads())) {
                        Links::link($contact, Leads::getOne(config('services.amocrm.domain')), 'contacts');
                        continue;
                    }

                    $statusId = Leads::getOne($contact->getLeads()[0]->getId())->getStatusId();
                    if ($statusId !== 0) {
                        $newCustomer = Customers::addOne();
                        $contact->setIsMain(false);
                        Links::link($contact, $newCustomer, 'contacts');
                    }
                }
            }

            $this->isCustomerCreated = true;
            return true;
        } catch (AmoCRMApiNoContentException $e) {}

        return false;
    }

    private function add(): void
    {
        $validated = $this->validated;

        $contact = new ContactModel();
        $contact->setFirstName($validated["firstname"])
            ->setLastName($validated["lastname"]);

        $contactsCustomFieldsService = ApiClient::get()
            ->customFields(EntityTypesInterface::CONTACTS);
        $fieldsCollection = $contactsCustomFieldsService->get();

        // set gender value to gender field
        self::addCustomField(
            $fieldsCollection,
            "GENDER",
            $contact,
            new TextCustomFieldValuesModel(),
            fn () => (new TextCustomFieldValueCollection())
                ->add((new TextCustomFieldValueModel())
                    ->setValue($validated['gender']))
        );


        // set phone value to phone field
        self::addCustomField(
            $fieldsCollection,
            "PHONE",
            $contact,
            new MultitextCustomFieldValuesModel(),
            fn () => (new MultitextCustomFieldValueCollection())
                ->add((new MultitextCustomFieldValueModel())
                    ->setEnum('WORK')
                    ->setValue($validated["phone"]))
        );

        // set email value to email field
        self::addCustomField(
            $fieldsCollection,
            "EMAIL",
            $contact,
            new MultitextCustomFieldValuesModel(),
            fn () => (new MultitextCustomFieldValueCollection())
                ->add((new MultitextCustomFieldValueModel())
                    ->setEnum('WORK')
                    ->setValue($validated["email"]))
        );

        // set age value to age field
        self::addCustomField(
            $fieldsCollection,
            "AGE",
            $contact,
            new NumericCustomFieldValuesModel(),
            fn () => (new NumericCustomFieldValueCollection())
                ->add((new NumericCustomFieldValueModel())
                    ->setValue($validated["age"]))
        );

        $contact->setResponsibleUserId(Users::getRandomId());

        $this->contact = $contact;
    }

    public static function addCustomField(
        CustomFieldsCollection $fieldsCollection,
        string $code,
        ContactModel $contact,
        BaseCustomFieldValuesModel $fieldValuesModel,
        callable $callback
    ): void {
        $field = $fieldsCollection->getBy("code", $code);

        if (empty($field)) {
            throw new \Exception("Please create custom field");
        }

        $customFieldsValue = $contact->getCustomFieldsValues();
        $fieldsCollection = $customFieldsValue ?? new CustomFieldsValuesCollection();

        $fieldsCollection->add(
            $fieldValuesModel
                ->setFieldId($field->getId())
                ->setFieldName($field->getName())
                ->setFieldCode($field->getCode())
                ->setValues(
                    $callback()
                )
        );

        if (empty($customFieldsValue)) {
            $contact->setCustomFieldsValues($fieldsCollection);
        }
    }

    public function save(): BaseApiModel
    {
        return ApiClient::get()->contacts()->addOne($this->contact);
    }
}
