<?php

namespace Tests\Feature;

use AmoCRM\Client\AmoCRMApiClient;
use App\Services\AmoCrm\ApiClient;
use Tests\TestCase;

class AmoCrmAuthTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_get_api_client(): void
    {
        $apiClient = ApiClient::get();
        $this->assertInstanceOf(AmoCRMApiClient::class, $apiClient);
        
    }
}
