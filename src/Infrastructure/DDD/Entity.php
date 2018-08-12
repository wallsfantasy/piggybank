<?php
declare(strict_types=1);

namespace App\Infrastructure\DDD;

interface Entity
{
    public function sameIdentityAs(Entity $entity): bool;
}
