<?php

namespace App\Tests\User\Model;

use App\User\Model\User;
use Assert\Assertion;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testRegister()
    {
        $userId = 'bd4824c6-82b2-4ce8-b6f5-4f3ac85ce9db';
        $userName = 'test';
        $userEmail = 'abc@test.com';

        $user = User::register($userId, $userName, $userEmail);

        $this->assertTrue(Assertion::uuid($user->id));
        $this->assertTrue(Assertion::string($user->name));
        $this->assertTrue(Assertion::email($user->email));
    }
}
