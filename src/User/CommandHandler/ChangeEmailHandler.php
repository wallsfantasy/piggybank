<?php
declare(strict_types=1);

namespace App\User\CommandHandler;

use App\User\Command\ChangeEmail;
use App\User\EventStore\UserEventStore;
use App\User\Exception\ChangeEmailAlreadyExist;
use App\User\Model\User;
use App\User\Projection\UserReadModelRepo;

class ChangeEmailHandler
{
    /** @var UserEventStore */
    private $userEventStore;

    /** @var UserReadModelRepo */
    private $userReadModelRepo;

    public function __construct(UserEventStore $userEventStore, UserReadModelRepo $userReadModelRepo)
    {
        $this->userEventStore = $userEventStore;
        $this->userReadModelRepo = $userReadModelRepo;
    }

    public function __invoke(ChangeEmail $command): void
    {
        $payload = $command->payload();

        $existUser = $this->userReadModelRepo->findOneByEmailAddress($payload['email']);
        if ($existUser !== null) {
            throw ChangeEmailAlreadyExist::create($existUser->email);
        }

        /** @var User $user */
        $user = $this->userEventStore->getAggregateRoot($payload['id']);
        $user->changeEmail($payload['email']);

        $this->userEventStore->saveAggregateRoot($user);
    }
}
