<?php

namespace Aeolun\NginxConfig\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DisableHost extends Command
{
    private $config;

	public function __construct($name, $config) {
		parent::__construct($name);

		$this->config = $config;
	}

    protected function configure()
    {
        $this->setName("host:disable")->setDescription("Removes a host from sites-available from sites-enabled if necessary.")->addArgument('host');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $available = $this->config['nginx_available'];
        $enabled = $this->config['nginx_enabled'];

        $host = $input->getArgument('host');

        if ( ! file_exists($available.$host)) {
        	$output->writeln('That host does not exist.');
        }

        if ( ! file_exists($enabled.$host)) {
        	$output->writeln('That host is not enabled.');
        }

        unlink($enabled.$host);
        
        $output->writeln(sprintf("Disabled host %s", $host));
    }
}