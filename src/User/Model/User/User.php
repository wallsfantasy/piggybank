<?php
declare(strict_types=1);

namespace App\User\Model\User;

use App\Infrastructure\DDD\Entity;

class User implements Entity
{
    /** @var UserId */
    public $id;

    /** @var UserName */
    public $name;

    /** @var UserEmail */
    public $email;

    public static function register(UserId $id, UserName $name, UserEmail $email): self
    {
        $self = new self();
        $self->id = $id;
        $self->name = $name;
        $self->email = $email;

        return $self;
    }

    public function sameIdentityAs(Entity $another): bool
    {
        /** @var self $another */
        return self::class === \get_class($another) && $this->id === $another->id;
    }
}
