<?php
declare(strict_types=1);

namespace App\User\Model\User;

use App\Infrastructure\DDD\ValueObject;
use Assert\Assert;

final class UserName implements ValueObject
{
    /** @var string */
    public $name;

    public static function create(string $name): self
    {
        return new self($name);
    }

    private function __construct(string $name)
    {
        Assert::that($name)->notBlank();

        $this->name = $name;
    }

    public function sameValueAs(ValueObject $another): bool
    {
        /** @var self $another */
        return self::class === \get_class($another) && $this->name === $another->name;
    }
}
