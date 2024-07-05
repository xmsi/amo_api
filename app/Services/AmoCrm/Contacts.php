<?php

namespace App\Services\AmoCrm;

use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\BaseCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\TextCustomFieldValueModel;

class Contacts
{
    public static function add(array $validated)
    {
        $contact = new ContactModel();
        $contact->setFirstName($validated["firstname"])
            ->setLastName($validated["lastname"]);

        $phoneField = self::addCustomField($contact, (new MultitextCustomFieldValuesModel()), 'PHONE');
        //Установим значение поля
        $phoneField->setValues(
            (new MultitextCustomFieldValueCollection())
                ->add(
                    (new MultitextCustomFieldValueModel())
                        ->setEnum('WORKDD')
                        ->setValue($validated["phone"])
                )
        );

        $ageField = self::addCustomField($contact, (new TextCustomFieldValuesModel()), 'GENDER');
        $ageField->setValues(
            (new TextCustomFieldValueCollection())
                ->add(
                    (new TextCustomFieldValueModel())
                        ->setValue($validated["phone"])
                )
        );

        
    }

    private static function addCustomField(
        ContactModel $contact,
        BaseCustomFieldValuesModel $field,
        string $name
    ): BaseCustomFieldValuesModel 
    {
        $customFields = $contact->getCustomFieldsValues();
        //Получим значение поля по его коду
        $newField = $customFields->getBy('fieldCode', $name);

        // Если значения нет, то создадим новый объект поля и добавим его в коллекцию значений
        if (empty($newField)) {
            $newField = $field->setFieldCode($name);
            $customFields->add($newField);
        }

        return $newField;
    }
}
