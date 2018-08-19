<?php

namespace App\Tests\User\Model;

use App\User\Model\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testRegister()
    {
        $userId = 'bd4824c6-82b2-4ce8-b6f5-4f3ac85ce9db';
        $userName = 'test';
        $userEmail = 'abc@test.com';

        $user = User::register($userId, $userName, $userEmail);

        $this->assertAttributeSame($userId, 'id', $user);
        $this->assertAttributeSame($userName, 'name', $user);
        $this->assertAttributeSame($userEmail, 'email', $user);
    }
}
