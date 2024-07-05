<?php

namespace App\Services\AmoCrm;

class Users {
    public static function all() {
        $apiClient = ApiClient::get();
        return $apiClient->users()->get();
    }
}