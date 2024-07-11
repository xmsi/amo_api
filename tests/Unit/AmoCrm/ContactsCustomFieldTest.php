<?php

namespace Tests\Unit\AmoCrm;

use PHPUnit\Framework\TestCase;
use AmoCRM\Collections\CustomFields\CustomFieldsCollection;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFields\TextCustomFieldModel;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\TextCustomFieldValueModel;
use App\Services\AmoCrm\Contacts;
use App\Services\AmoCrm\Utils\Fields;

class ContactsCustomFieldTest extends TestCase
{
    public function testAddCustomField()
    {
        $fieldsCollection = new CustomFieldsCollection();
        $textField = new TextCustomFieldModel();
        $textField->setId(436001)
                  ->setCode('GENDER')
                  ->setName('Пол');
        $fieldsCollection->add($textField);

        $contact = new ContactModel();
        $fieldValuesModel = new TextCustomFieldValuesModel();

        Fields::setCustomField(
            $fieldsCollection,
            'GENDER',
            $contact,
            $fieldValuesModel,
            function() {
                return (new TextCustomFieldValueCollection())
                        ->add((new TextCustomFieldValueModel())
                            ->setValue('male'));
            }
        );

        $customFieldsValues = $contact->getCustomFieldsValues();
        $this->assertNotNull($customFieldsValues);
        $this->assertCount(1, $customFieldsValues);
        
        $genderField = $customFieldsValues->first();
        $this->assertInstanceOf(TextCustomFieldValuesModel::class, $genderField);
        $this->assertEquals(436001, $genderField->getFieldId());
        $this->assertEquals('GENDER', $genderField->getFieldCode());
        $this->assertEquals('Пол', $genderField->getFieldName());

        $genderValues = $genderField->getValues();
        $this->assertCount(1, $genderValues);
        $this->assertEquals('male', $genderValues->first()->getValue());
    }

    public function testAddCustomFieldThrowsExceptionForInvalidField()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Please create custom field');

        $fieldsCollection = new CustomFieldsCollection();
        $contact = new ContactModel();
        $fieldValuesModel = new TextCustomFieldValuesModel();

        Fields::setCustomField(
            $fieldsCollection,
            'INVALID_CODE',
            $contact,
            $fieldValuesModel,
            function() {
                return (new TextCustomFieldValueCollection())
                        ->add((new TextCustomFieldValueModel())
                            ->setValue('test'));
            }
        );
    }
}