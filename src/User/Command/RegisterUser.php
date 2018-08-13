<?php
declare(strict_types=1);

namespace App\User\Command;

use Assert\Assert;
use Assert\Assertion;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;

final class RegisterUser extends Command implements PayloadConstructable
{
    use PayloadTrait;

    public static function withData(string $id, string $name, string $email): self
    {
        return new self([
            'id' => $id,
            'name' => $name,
            'email' => $email,
        ]);
    }

    protected function setPayload(array $payload): void
    {
        Assert::lazy()
            ->that($payload, 'payload')->keyExists('id')
            ->that($payload, 'payload')->keyExists('name')
            ->that($payload, 'payload')->keyExists('email')
            ->verifyNow();

        Assert::lazy()
            ->that($payload['id'], 'id')->uuid()
            ->that($payload['name'], 'name')->string()
            ->that($payload['email'], 'email')->email()
            ->verifyNow();

        $this->payload = $payload;
    }
}
