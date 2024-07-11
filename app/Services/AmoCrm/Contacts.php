<?php

namespace App\Services\AmoCrm;

use AmoCRM\Exceptions\AmoCRMApiNoContentException;
use AmoCRM\Filters\ContactsFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\NumericCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\NumericCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\NumericCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\TextCustomFieldValueModel;
use App\Services\AmoCrm\Constants\Fields as CustomFieldsCode;
use App\Services\AmoCrm\Constants\Leads;
use App\Services\AmoCrm\Utils\Fields;

class Contacts
{
    private ?ContactModel $contact;
    private bool $isHaveDouble = false;

    public function __construct(private array $validated)
    {
        if (!$this->haveDouble()) {
            $this->add();
        }
    }

    private function getValidated(): array
    {
        return $this->validated;
    }

    public function getContact(): ContactModel
    {
        return $this->contact;
    }

    private function setContact(ContactModel $value): void
    {
        $this->contact = $value;
    }

    public function getIsHaveDouble(): bool
    {
        return $this->isHaveDouble;
    }

    private function setIsHaveDouble(bool $value): void
    {
        $this->isHaveDouble = $value;
    }

    private function haveDouble(): bool
    {
        $filter = new ContactsFilter();
        $phone = Fields::normalizePhone($this->validated['phone']);
        $filter->setQuery($phone);
        try {
            $contactsCollection = ApiClient::get()->contacts()->get(
                $filter,
                [EntityTypesInterface::LEADS]
            );
            dd($contactsCollection->count());
            $contactsCollection->getBy(
                'customFieldsValues',
                (new MultitextCustomFieldValuesModel())
                    ->setFieldCode(CustomFieldsCode::PHONE)
                    ->setValues(
                        (new MultitextCustomFieldValueCollection())
                            ->add((new MultitextCustomFieldValueModel())
                                ->setEnum(CustomFieldsCode::ENUM_WORK)
                                ->setValue($this->getValidated()['phone']))
                    )
            );

            if (!$contactsCollection->isEmpty()) {
                foreach ($contactsCollection as $contact) {
                    if (empty($contact->getLeads())) {
                        $lead = new Leads($contact->getId());
                        $lead->save();
                        Links::link(
                            $contact,
                            $lead->getLeadsCollection()->first(),
                            EntityTypesInterface::CONTACTS
                        );
                        continue;
                    }

                    $statusId = Leads::getOne($contact->getLeads()[0]->getId())
                        ->getStatusId();

                    if ($statusId === Leads::SUCCESS_STATUS_ID) {
                        $newCustomer = Customers::addOne();
                        $contact->setIsMain(false);
                        Links::link($contact, $newCustomer, EntityTypesInterface::CONTACTS);
                    }
                }
            }

            $this->setIsHaveDouble(true);

            return true;
        } catch (AmoCRMApiNoContentException $e) {
            dd(0);
        }

        return false;
    }

    private function add(): void
    {
        $validated = $this->getValidated();

        $contact = new ContactModel();
        $contact->setFirstName($validated['firstname'])
            ->setLastName($validated['lastname']);

        $contactsCustomFieldsService = ApiClient::get()
            ->customFields(EntityTypesInterface::CONTACTS);
        $fieldsCollection = $contactsCustomFieldsService->get();

        // set gender value to gender field
        Fields::setCustomField(
            $fieldsCollection,
            CustomFieldsCode::GENDER,
            $contact,
            new TextCustomFieldValuesModel(),
            fn () => (new TextCustomFieldValueCollection())
                ->add((new TextCustomFieldValueModel())
                    ->setValue($validated['gender']))
        );


        // set phone value to phone field
        Fields::setCustomField(
            $fieldsCollection,
            CustomFieldsCode::PHONE,
            $contact,
            new MultitextCustomFieldValuesModel(),
            fn () => (new MultitextCustomFieldValueCollection())
                ->add((new MultitextCustomFieldValueModel())
                    ->setEnum(CustomFieldsCode::ENUM_WORK)
                    ->setValue($validated['phone']))
        );

        // set email value to email field
        Fields::setCustomField(
            $fieldsCollection,
            CustomFieldsCode::EMAIL,
            $contact,
            new MultitextCustomFieldValuesModel(),
            fn () => (new MultitextCustomFieldValueCollection())
                ->add((new MultitextCustomFieldValueModel())
                    ->setEnum(CustomFieldsCode::ENUM_WORK)
                    ->setValue($validated['email']))
        );

        // set age value to age field
        Fields::setCustomField(
            $fieldsCollection,
            CustomFieldsCode::AGE,
            $contact,
            new NumericCustomFieldValuesModel(),
            fn () => (new NumericCustomFieldValueCollection())
                ->add((new NumericCustomFieldValueModel())
                    ->setValue($validated['age']))
        );

        $contact->setResponsibleUserId(Users::getRandomId());

        $this->setContact($contact);
    }

    public function save(): BaseApiModel
    {
        return ApiClient::get()->contacts()->addOne($this->getContact());
    }
}
