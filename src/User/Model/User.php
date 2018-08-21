<?php
declare(strict_types=1);

namespace App\User\Model;

use App\User\Event\UserRegistered;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;

class User extends AggregateRoot
{
    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $email;

    public static function register(string $id, string $name, string $email): self
    {
        $self = new self();

        $self->recordThat(UserRegistered::withData($id, $name, $email));

        return $self;
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

    private function whenUserRegistered(UserRegistered $event): void
    {
        $payload = $event->payload();

        $this->id = $event->aggregateId();
        $this->name = $payload['name'];
        $this->email = $payload['email'];
    }
}
