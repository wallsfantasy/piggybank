<?php

namespace App\User\CommandHandler;

use App\User\Command\RegisterUser;

class RegisterUserHandler
{
    public function __invoke(RegisterUser $command): void
    {
        // @todo: implement event sourcing
    }
}
