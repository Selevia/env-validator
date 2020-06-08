<?php

use Selevia\Common\EnvValidator\Command\EnvValidatorCommandFactory;
use Symfony\Component\Console\Input\ArrayInput;
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

$command->run(new ArrayInput([]), new ConsoleOutput());
