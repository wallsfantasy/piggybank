<?php
declare(strict_types=1);

namespace App\User\Command;

use Assert\Assert;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;

final class ChangeEmail extends Command implements PayloadConstructable
{
    use PayloadTrait;

    protected function setPayload(array $payload): void
    {
        Assert::lazy()
            ->that($payload, 'payload')->keyExists('id')
            ->that($payload, 'payload')->keyExists('email')
            ->verifyNow();

        Assert::lazy()
            ->that($payload['id'], 'id')->uuid()
            ->that($payload['email'], 'email')->email()
            ->verifyNow();

        $this->payload = $payload;
    }
}
