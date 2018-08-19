<?php
declare(strict_types=1);

namespace App\User\Exception;

final class RegisterUserAlreadyExists extends \InvalidArgumentException
{
    public static function create(string $email, string $id): RegisterUserAlreadyExists
    {
        return new self("User with ${email} already exists. ID: ${id}");
    }
}
