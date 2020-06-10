<?php

use Selevia\EnvValidator\Command\EnvValidatorCommandFactory;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;


foreach ([__DIR__ . '/../../../autoload.php', __DIR__ . '/../vendor/autoload.php'] as $file) {
    if (file_exists($file)) {
        $loader = require_once $file;

        break;
    }
}

if (!$loader) {
    throw new RuntimeException('vendor/autoload.php could not be found');
}

$commandFactory = new EnvValidatorCommandFactory();
$command = $commandFactory();

$app = new Application();
$app->add($command);
$app->setDefaultCommand($command->getName(), true);

$app->run(new ArgvInput(), new ConsoleOutput());
