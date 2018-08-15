<?php
declare(strict_types=1);

namespace App\User\Model;

use App\Infrastructure\DDD\Entity;
use App\User\Event\UserRegistered;
use Assert\Assertion;
use Prooph\EventSourcing\Aggregate\EventProducerTrait;
use Prooph\EventSourcing\AggregateChanged;

class User implements Entity
{
    use EventProducerTrait;

    /** @var string */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $email;

    public static function register(string $id, string $name, string $email): self
    {
        $self = new self();

        Assertion::uuid($id);
        Assertion::string($name);
        Assertion::email($email);

        $self->id = $id;
        $self->name = $name;
        $self->email = $email;

        $self->recordThat(UserRegistered::withData($id, $name, $email));

        return $self;
    }

    public function sameIdentityAs(Entity $another): bool
    {
        /** @var self $another */
        return self::class === \get_class($another) && $this->id === $another->id;
    }

    protected function aggregateId(): string
    {
        return $this->id;
    }

    protected function apply(AggregateChanged $e): void
    {
        $handler = 'when' . implode(array_slice(explode('\\', get_class($e)), -1));

        if (! method_exists($this, $handler)) {
            throw new \RuntimeException(sprintf(
                'Missing event handler method %s for aggregate root %s',
                $handler,
                get_class($this)
            ));
        }

        $this->{$handler}($e);
    }
}
