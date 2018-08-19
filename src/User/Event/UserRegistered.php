<?php
declare(strict_types=1);

namespace App\User\Event;

use Prooph\EventSourcing\AggregateChanged;

class UserRegistered extends AggregateChanged
{
    public static function withData(string $id, string $name, string $email): self
    {
        return self::occur($id, [
            'name' => $name,
            'email' => $email,
        ]);
    }
}
