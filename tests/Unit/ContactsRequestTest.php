<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ContactsRequest;
use PHPUnit\Framework\Attributes\DataProvider;

class ContactsRequestTest extends TestCase
{
    #[DataProvider('validationDataProvider')]
    public function testValidation(array $data, bool $expected)
    {
        $request = new ContactsRequest();
        $rules = $request->rules();
        
        $validator = Validator::make($data, $rules);
        $this->assertEquals($expected, $validator->passes());
    }

 
    public static function validationDataProvider()
    {
        return [
            'missing all fields' => [
                'data' => [

                ],
                'expected' => false,
            ],
            'valid data' => [
                'data' => [
                    'firstname' => 'John',
                    'lastname' => 'Doe',
                    'age' => 25,
                    'gender' => 'male',
                    'phone' => '1234567890',
                    'email' => 'john.doe@example.com',
                ],
                'expected' => true,
            ],
            'age not numeric' => [
                'data' => [
                    'firstname' => 'John',
                    'lastname' => 'Doe',
                    'age' => 'twenty-five',
                    'gender' => 'male',
                    'phone' => '1234567890',
                    'email' => 'john.doe@example.com',
                ],
                'expected' => false,
            ],
            'age out of range' => [
                'data' => [
                    'firstname' => 'John',
                    'lastname' => 'Doe',
                    'age' => 7,
                    'gender' => 'male',
                    'phone' => '1234567890',
                    'email' => 'john.doe@example.com',
                ],
                'expected' => false,
            ],
            'invalid gender' => [
                'data' => [
                    'firstname' => 'John',
                    'lastname' => 'Doe',
                    'age' => 25,
                    'gender' => 'other',
                    'phone' => '1234567890',
                    'email' => 'john.doe@example.com',
                ],
                'expected' => false,
            ],
            'invalid email' => [
                'data' => [
                    'firstname' => 'John',
                    'lastname' => 'Doe',
                    'age' => 25,
                    'gender' => 'male',
                    'phone' => '1234567890',
                    'email' => 'john.doe',
                ],
                'expected' => false,
            ],
        ];
    }
}
