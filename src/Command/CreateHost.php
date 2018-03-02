<?php

namespace Aeolun\NginxConfig\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class CreateHost extends Command
{
    private $config;

	public function __construct($name, $config) {
		parent::__construct($name);

		$this->config = $config;
	}

    protected function configure()
    {
        $this->setName("host:create")->setDescription("Adds a new host to sites-available, based on the default template.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $phpQuestion = new ConfirmationQuestion('Create host to execute PHP? (Y/n) ', true);
        $indexFallbackQuestion = new ConfirmationQuestion('Do you want to fall back to index.php if the requested file does not exist? (Y/n) ', true);
        $hostnameQuestion = new Question('What is the hostname you would like to use? ', false);
        $webrootQuestion = new Question('What is the webroot you would like to use? ', false);
        $hostsEntryQuestion = new ConfirmationQuestion('Do you want to add an entry for this host to the hostsfile (pointing to 127.0.0.1)? (Y/n) ', true);

        $includePhp = $helper->ask($input, $output, $phpQuestion);
        $indexFallback = false;
        if ($includePhp) {
        	$indexFallback = $helper->ask($input, $output, $indexFallbackQuestion);
        }
        $addToHosts = $helper->ask($input, $output, $hostsEntryQuestion);
		$hostname = $helper->ask($input, $output, $hostnameQuestion);
		$webroot = $helper->ask($input, $output, $webrootQuestion);
		
        
        $configFile = file_get_contents('templates/vhost.config');
        $phpConfig = file_get_contents('templates/php.config');

        $configFile = str_replace([
        	'{{root}}',
        	'{{server_name}}',
        	'{{php}}',
        	'{{index_fallback}}'
        ], [
        	$webroot,
        	$hostname,
        	$includePhp ? $phpConfig : '',
        	$indexFallback ? 'index.php ' : ''
        ], $configFile);

        file_put_contents($this->config['nginx_available'].$hostname, $configFile);

        if ($addToHosts) {
        	$lines = file_get_contents($this->config['hosts_file']);

        	if (strpos($hostname, $lines) !== false) {
        		$output->writeln("Host already in hosts file.");
        	}

        	file_put_contents($this->config['hosts_file'], sprintf("\n%s\t%s", '127.0.0.1', $hostname), FILE_APPEND);
        }
    }
}
