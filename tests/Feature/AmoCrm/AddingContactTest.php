<?php

namespace Tests\Feature\AmoCrm;


use Tests\TestCase;

class AddingContactTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
