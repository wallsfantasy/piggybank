<?php
declare(strict_types=1);

namespace App\User\EventHandler;

use App\User\Event\EmailChanged;

class EmailChangedHandler
{
    public function __invoke(EmailChanged $event): void
    {
        // @todo: just a test so nothing yet
    }
}
