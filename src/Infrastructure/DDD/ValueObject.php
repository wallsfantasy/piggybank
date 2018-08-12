<?php
declare(strict_types=1);

namespace App\Infrastructure\DDD;

interface ValueObject
{
    public function sameValueAs(ValueObject $another): bool;
}
