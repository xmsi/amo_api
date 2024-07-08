# API for AmoCRM
***I have used laravel11 sail + [amocrm/amocrm-api-library](https://github.com/amocrm/amocrm-api-php)***

## Contacts 

#### Services/AmoCrm/Contacts.php

adding new field to contacts form in account (use only once if you need, otherwise you can create from admin panel also)\
- setName is required
```
$contactsCustomFieldsService = ApiClient::get()
            ->customFields(EntityTypesInterface::CONTACTS);

         try {
            $field = $contactsCustomFieldsService->addOne(
                (new TextCustomFieldModel())
                    // setName is required
                    ->setName('gender')
                    ->setCode('GENDER')
            );
        } catch (AmoCRMApiException $e) {
            echo $e;
            die;
        } 

        $contact->setCustomFieldsValues(
            (new CustomFieldsValuesCollection())
                ->add(
                    (new TextCustomFieldValuesModel())
                        ->setFieldId($field->getId())
                        ->setFieldName($field->getName())
                        ->setFieldCode($field->getCode())
                        ->setValues(
                            (new TextCustomFieldValueCollection())
                                ->add((new TextCustomFieldValueModel())->setValue($validated['gender']))
                        )
                )
        );

```