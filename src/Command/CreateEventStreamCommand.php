<?php
declare(strict_types=1);

namespace App\Command;

use App\User\EventStore\UserEventStore;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Stream;
use Prooph\EventStore\StreamName;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateEventStreamCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('event-store:event-stream:create')
            ->setDescription('Create event streams.')
            ->setHelp('This command creates the event streams');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EventStore $eventStore */
        $eventStore = $this->getContainer()->get('prooph_event_store.user_store');

        $eventStore->create(new Stream(new StreamName(UserEventStore::STREAM_USER), new \ArrayIterator([])));
        $output->writeln('<info>Event stream was created successfully.</info>');
    }
}
