<?php
declare(strict_types=1);

namespace App\User\Event;

use Prooph\EventSourcing\AggregateChanged;

class EmailChanged extends AggregateChanged
{
    public static function withData(string $id, string $email): self
    {
        return self::occur($id, [
            'email' => $email,
        ]);
    }
}
