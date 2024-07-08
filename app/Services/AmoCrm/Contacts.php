<?php

namespace App\Services\AmoCrm;

use AmoCRM\Collections\CustomFields\CustomFieldsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFields\TextCustomFieldModel;
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

use function PHPUnit\Framework\isEmpty;

class Contacts
{
    public static function add(array $validated)
    {
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
            function () use ($validated) {
                return (new TextCustomFieldValueCollection())
                    ->add((new TextCustomFieldValueModel())->setValue($validated['gender']));
            }
        );


        // set phone value to phone field
        self::addCustomField(
            $fieldsCollection,
            "PHONE",
            $contact,
            new MultitextCustomFieldValuesModel(),
            function () use ($validated) {
                return (new MultitextCustomFieldValueCollection())
                            ->add((new MultitextCustomFieldValueModel())
                                    ->setEnum('WORK')
                                    ->setValue($validated["phone"])
                            );
            }
        );

        // set email value to email field
        self::addCustomField(
            $fieldsCollection,
            "EMAIL",
            $contact,
            new MultitextCustomFieldValuesModel(),
            function () use ($validated) {
                return (new MultitextCustomFieldValueCollection())
                            ->add((new MultitextCustomFieldValueModel())
                                    ->setEnum('WORK')
                                    ->setValue($validated["email"])
                            );
            }
        );

        dd($fieldsCollection);


        // ApiClient::get()->contacts()->addOne($contact);
    }

    private static function addCustomField(
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
}
