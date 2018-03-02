<?php

namespace Aeolun\NginxConfig\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EnableHost extends Command
{
	private $config;

	public function __construct($name, $config) {
		parent::__construct($name);

		$this->config = $config;
	}

    protected function configure()
    {
        $this->setName("host:enable")->setDescription("Adds a host from sites-available to sites-enabled if necessary.")->addArgument('host');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $available = $this->config['nginx_available'];
        $enabled = $this->config['nginx_enabled'];

        $host = $input->getArgument('host');

        if ( ! file_exists($available.$host)) {
        	$output->writeln('That host does not exist.');
        }

        if (file_exists($enabled.$host)) {
        	$output->writeln('That host is already enabled.');
        }

        symlink($available.$host, $enabled.$host);

        $output->writeln(sprintf("Enabled host %s", $host));
    }
}