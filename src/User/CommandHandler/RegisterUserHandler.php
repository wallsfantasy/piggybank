<?php
declare(strict_types=1);

namespace App\User\CommandHandler;

use App\User\Command\RegisterUser;
use App\User\EventStore\UserEventStore;
use App\User\Exception\RegisterUserAlreadyExists;
use App\User\Model\User;
use App\User\Projection\UserReadModelRepo;

class RegisterUserHandler
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

    public function __invoke(RegisterUser $command): void
    {
        $payload = $command->payload();

        $existUser = $this->userReadModelRepo->findOneByEmailAddress($payload['email']);
        if ($existUser !== null) {
            throw RegisterUserAlreadyExists::create($existUser->email, $existUser->id);
        }

        $user = User::register($payload['id'], $payload['name'], $payload['email']);

        $this->userEventStore->saveAggregateRoot($user);
    }
}
