<?php

namespace App\Services\AmoCrm\Utils;

use AmoCRM\Collections\CustomFields\CustomFieldsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\BaseCustomFieldValuesModel;
use App\Services\AmoCrm\Constants\Fields as CustomFieldsData;

class Fields
{
    public static function setCustomField(
        CustomFieldsCollection $fieldsCollection,
        string $code,
        ContactModel $contact,
        BaseCustomFieldValuesModel $fieldValuesModel,
        callable $callback
    ): void {
        $field = $fieldsCollection->getBy('code', $code);

        if (empty($field)) {
            throw new \Exception('Please create custom field');
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
        // delete all uneccessary chars from string
        $phoneNumber = str_replace([' ', '-', '(', ')'], '', $phoneNumber);

        // if phoneNumber hasn't +, then return without modifications
        if (!preg_match('/^\+/', $phoneNumber) || strlen($phoneNumber) < CustomFieldsData::PHONE_LAST_PART_LENGTH) {
            return $phoneNumber;
        }

        $getLastChars = substr($phoneNumber, -CustomFieldsData::PHONE_LAST_PART_LENGTH);

        return $getLastChars;
    }
}
