<?php

namespace App\Services\AmoCrm;

use AmoCRM\Collections\UsersCollection;
use AmoCRM\Models\UserModel;

class Users {
    public static function all(): ?UsersCollection {
        return ApiClient::get()->users()->get();
    }

    public static function getRandomId(): int {
        $users = self::all();
        
        if (empty($users)) {
            throw new \Exception("There is no User", 404);
        }

        $random = rand()&($users->count() - 1);
        return $users[$random]->getId();
    }
}