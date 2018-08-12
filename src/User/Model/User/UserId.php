<?php
declare(strict_types=1);

namespace App\User\Model\User;

use App\Infrastructure\DDD\ValueObject;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class UserId implements ValueObject
{
    /** @var UuidInterface */
    private $uuid;

    public static function generate(): self
    {
        return new self(Uuid::uuid4());
    }

    public static function create(string $userId): self
    {
        return new self(Uuid::fromString($userId));
    }

    private function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    public function sameValueAs(ValueObject $another): bool
    {
        /** @var self $another */
        return self::class === \get_class($another) && $this->uuid->equals($another->uuid);
    }
}
