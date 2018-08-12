<?php

namespace App\Tests\User\Model;

use App\User\Model\User\User;
use App\User\Model\User\UserEmail;
use App\User\Model\User\UserId;
use App\User\Model\User\UserName;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testRegister()
    {
        $userId = UserId::create('bd4824c6-82b2-4ce8-b6f5-4f3ac85ce9db');
        $userName = UserName::create('test');
        $userEmail = UserEmail::create('abc@test.com');

        $user = User::register($userId, $userName, $userEmail);

        $this->assertTrue($user->id->sameValueAs($userId));
        $this->assertTrue($user->name->sameValueAs($userName));
        $this->assertTrue($user->email->sameValueAs($userEmail));
    }
}
