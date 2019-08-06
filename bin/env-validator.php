<?php

use Selevia\Common\Command\EnvValidatorCommand;
use Selevia\Common\Command\EnvValidatorCommandFactory;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\FactoryCommandLoader;


require_once __DIR__ . "/../vendor/autoload.php";

$commandLoader = new FactoryCommandLoader([
    EnvValidatorCommand::COMMAND_NAME => new EnvValidatorCommandFactory,
]);

$application = new Application();
$application->setCommandLoader($commandLoader);

$application->run();
