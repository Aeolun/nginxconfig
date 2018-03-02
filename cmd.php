#!/usr/bin/env php
<?php

chdir(__DIR__);

require __DIR__.'/vendor/autoload.php';

use Aeolun\NginxConfig\Command\CreateHost;
use Aeolun\NginxConfig\Command\DisableHost;
use Aeolun\NginxConfig\Command\EnableHost;
use Aeolun\NginxConfig\Command\ListHosts;
use Aeolun\NginxConfig\Command\RemoveHost;
use Symfony\Component\Console\Application;

$config = include_once 'config.php';

$application = new Application();

$application->add(new CreateHost(null, $config));
$application->add(new RemoveHost());
$application->add(new EnableHost(null, $config));
$application->add(new DisableHost(null, $config));
$application->add(new ListHosts(null, $config));

$application->run();