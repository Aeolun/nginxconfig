<?php

namespace Aeolun\NginxConfig\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveHost extends Command
{
    protected function configure()
    {
        $this->setName("host:remove")->setDescription("Removes a host from sites-available, and sites-enabled if necessary.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Not implemented yet.");
    }
}