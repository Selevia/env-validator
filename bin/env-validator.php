<?php

use Selevia\Common\Command\EnvValidatorCommand;
use Selevia\Common\Command\EnvValidatorCommandFactory;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\FactoryCommandLoader;


foreach ([__DIR__ . '/../../../autoload.php', __DIR__ . '/../vendor/autoload.php'] as $file) {
    if (file_exists($file)) {
        $loader = require_once $file;

        break;
    }
}

if (! $loader) {
    throw new RuntimeException('vendor/autoload.php could not be found');
}

$commandLoader = new FactoryCommandLoader([
    EnvValidatorCommand::COMMAND_NAME => new EnvValidatorCommandFactory,
]);

$application = new Application();
$application->setCommandLoader($commandLoader);

$application->run();
