<?php

namespace App\Services\AmoCrm;

class ApiClient {
    public static function get()
    {
        $auth = Auth::getInstance();
        $auth->login();
        return $auth->getApiClient();
    }
}