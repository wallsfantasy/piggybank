<?php
declare(strict_types=1);

namespace App\User\EventHandler;

use App\User\Event\UserRegistered;

class UserRegisteredHandler
{
    public function __invoke(UserRegistered $event): void
    {
        // @todo: just a test so nothing yet
        //var_dump($event);
    }
}
