<?php

namespace Tests\Feature;


use Tests\TestCase;

class ContactsPostRequestTest extends TestCase
{
    /**
     * The URL to be used for the POST requests.
     *
     * @var string
     */
    private $url = '/api/contacts'; 

    /**
     * Test the POST request validation.
     *
     * @return void
     */
    public function test_post_request_validation()
    {
        $response = $this->postJson($this->url, [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'age' => 30,
            'gender' => 'male',
            'phone' => '1234567890',
            'email' => 'john.doe@example.com',
        ]);

        $response->assertStatus(200);
                //  ->assertJson([
                //      'firstname' => 'John',
                //      'lastname' => 'Doe',
                //      'age' => 30,
                //      'gender' => 'male',
                //      'phone' => '1234567890',
                //      'email' => 'john.doe@example.com',
                //  ]);
    }

    /**
     * Test the POST request with missing fields.
     *
     * @return void
     */
    public function test_post_request_required_errors()
    {
        $response = $this->postJson($this->url, []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                     'firstname', 
                     'lastname', 
                     'age', 
                     'gender', 
                     'phone', 
                     'email'
                 ]);
    }

    /**
     * Test the POST request with invalid age and email.
     *
     * @return void
     */
    public function test_post_request_invalid_age_email()
    {
        $response = $this->postJson($this->url, [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'age' => 'invalid_age',
            'gender' => 'male',
            'phone' => '1234567890',
            'email' => 'invalid_email',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['age', 'email']);
    }
}
