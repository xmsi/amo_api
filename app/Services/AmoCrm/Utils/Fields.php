<?php

namespace App\Services\AmoCrm\Utils;

use AmoCRM\Collections\CustomFields\CustomFieldsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\BaseCustomFieldValuesModel;

class Fields
{
    public static function setCustomField(
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

    public static function normalizePhone(string $phoneNumber): string
    {
        $countryCode = "+998";
    
        if (strpos($phoneNumber, $countryCode) !== false) {
            $phoneNumber = str_replace($countryCode, "", $phoneNumber);
        }

        $phoneNumber = str_replace(" ","", $phoneNumber);
        
        return $phoneNumber;

    }
}