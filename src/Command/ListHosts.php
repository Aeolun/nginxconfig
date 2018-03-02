<?php

namespace Aeolun\NginxConfig\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListHosts extends Command
{
	private $config;

	public function __construct($name, $config) {
		parent::__construct($name);

		$this->config = $config;
	}

    protected function configure()
    {
        $this->setName("host:list")->setDescription("Lists the available hosts and whether or not they are enabled.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $available = array_diff(scandir($this->config['nginx_available']), ['.', '..']);
        $enabled = array_diff(scandir($this->config['nginx_enabled']), ['.', '..']);

        $hosts = [];
        foreach($available as $host) {
        	$hosts[] = [
        		'name' => $host,
        		'enabled' => in_array($host, $enabled),
        	];
        }

        $output->writeln([
        	"Available Hosts",
        	"===============",
        	"",
        	sprintf("%-30s%-8s", "Name", "Enabled"),
        	"----------------------------------------------"
        ]);
        foreach($hosts as $host) {
        	$output->writeln(sprintf("%-30s%-8s", $host['name'], $host['enabled'] ? 'Yes' : 'No'));
        }
    }
}