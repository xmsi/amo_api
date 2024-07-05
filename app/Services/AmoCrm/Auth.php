<?php

namespace App\Services\AmoCrm;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\LongLivedAccessToken;

class Auth {
    private static ?self $instance; 
    private ?AmoCRMApiClient $apiClient;

    private function __construct() {}

    private function __clone() {}

    public static function getInstance(): self {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getApiClient(): ?AmoCRMApiClient {
        return $this->apiClient;
    }

    public function login(): void {
        if (!isset($this->apiClient)) {
            $this->apiClient = new AmoCRMApiClient();
            $longLivedAccessToken = new LongLivedAccessToken(config('services.amocrm.token'));
            $this->apiClient->setAccessToken($longLivedAccessToken)
                    ->setAccountBaseDomain(config('services.amocrm.domain'));
        }
    }
}