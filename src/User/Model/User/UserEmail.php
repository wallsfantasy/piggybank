<?php
declare(strict_types=1);

namespace App\User\Model\User;

use App\Infrastructure\DDD\ValueObject;
use Assert\Assert;

final class UserEmail implements ValueObject
{
    /** @var string */
    public $email;

    public static function create(string $email): self
    {
        return new self($email);
    }

    private function __construct(string $email)
    {
        Assert::that($email)->email();

        $this->email = $email;
    }

    public function sameValueAs(ValueObject $another): bool
    {
        /** @var self $another */
        return self::class === \get_class($another) && $this->email === $another->email;
    }
}
