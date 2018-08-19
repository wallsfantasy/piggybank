<?php
declare(strict_types=1);

namespace App\User\Projection;

final class UserReadModel
{
    /** @var string */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $email;

    public static function fromArray(array $data): self
    {
        return new self($data['id'], $data['name'], $data['email']);
    }

    private function __construct(string $id, string $name, string $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }
}
