<?php
declare(strict_types=1);

namespace App\User\Projection;

use App\User\Event\EmailChanged;
use App\User\Event\UserRegistered;
use App\User\EventStore\UserEventStore;
use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventStore\Projection\ReadModelProjector;

final class UserProjection implements ReadModelProjection
{
    public function project(ReadModelProjector $projector): ReadModelProjector
    {
        $projector->fromStream(UserEventStore::STREAM_USER)
            ->when([
                UserRegistered::class => function ($state, UserRegistered $event) {
                    $payload = $event->payload();

                    /** @var UserReadModelRepo $repo */
                    $repo = $this->readModel();
                    $repo->stack(
                        'add',
                        UserReadModel::fromArray([
                            'id' => $event->aggregateId(),
                            'name' => $payload['name'],
                            'email' => $payload['email'],
                        ])
                    );
                },
                EmailChanged::class => function ($state, EmailChanged $event) {
                    $payload = $event->payload();

                    echo(self::class . 'executed');
                    /** @var UserReadModelRepo $repo */
                    $repo = $this->readModel();
                    $repo->stack('changeEmail',
                        $event->aggregateId(),
                        $payload['email']
                    );
                },
            ]);

        return $projector;
    }
}
