<?php

namespace App\Services\AmoCrm;

use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Customers\CustomerModel;

class Customers
{
    public static function addOne(): BaseApiModel
    {
        $customer = new CustomerModel();
        $customer->setName("Покупатель ".uniqid());

        try {
            return ApiClient::get()->customers()->addOne($customer);
        } catch (AmoCRMApiException $e) {
            throw new \Exception("Could not created customer", 500);
            die;
        }
    }
}
