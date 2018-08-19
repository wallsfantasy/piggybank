<?php
declare(strict_types=1);

namespace App\Command;

use App\User\EventStore\UserEventStore;
use Prooph\EventStore\StreamName;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteEventStreamCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('event-store:event-stream:delete')
            ->setDescription('Delete event streams.')
            ->setHelp('This command deletes the event streams');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $eventStore = $this->getContainer()->get('prooph_event_store.user_store');

        $eventStore->delete(new StreamName(UserEventStore::STREAM_USER));
        $output->writeln('<info>Event stream was deleted successfully.</info>');
    }
}
