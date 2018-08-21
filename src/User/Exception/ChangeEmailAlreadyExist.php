<?php
declare(strict_types=1);

namespace App\User\Exception;

final class ChangeEmailAlreadyExist extends \InvalidArgumentException
{
    public static function create(string $email): self
    {
        return new self("Email address ${email} already registered");
    }
}
