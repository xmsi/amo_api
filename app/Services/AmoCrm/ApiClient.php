<?php

namespace App\Services\AmoCrm;

use AmoCRM\Client\AmoCRMApiClient;

class ApiClient {
    public static function get(): AmoCRMApiClient
    {
        $auth = Auth::getInstance();
        $auth->login();
        return $auth->getApiClient();
    }
}