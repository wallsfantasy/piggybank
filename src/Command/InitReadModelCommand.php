<?php

namespace App\Command;

use Prooph\EventStore\EventStore;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitReadModelCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('event-store:read-repository:initialize')
            ->setDescription('Initialize read model repositories.')
            ->setHelp('This command initialize read model repositories');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EventStore $eventStore */
        $userReadModelRepo = $this->getContainer()->get('user.projection.user_read_repository');

        if (! $userReadModelRepo->isInitialized()) {
            $userReadModelRepo->init();
        }

        $output->writeln('<info>Read model repositories initialized successfully.</info>');
    }
}
