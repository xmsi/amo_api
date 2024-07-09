<?php

namespace Tests\Feature;

use AmoCRM\Collections\UsersCollection;
use App\Services\AmoCrm\Users;
use Tests\TestCase;

class UsersTest extends TestCase
{
    public function test_users_not_empty(): void
    {
        $users = Users::all();

        $this->assertNotEmpty($users);
        $this->assertInstanceOf(UsersCollection::class, $users);
    }
}
