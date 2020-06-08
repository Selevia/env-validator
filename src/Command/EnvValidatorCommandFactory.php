<?php


namespace Selevia\EnvValidator\Command;


use Selevia\EnvValidator\Validator\DotEnvLoader;
use Selevia\EnvValidator\Validator\Status\StatusFactory;
use Selevia\EnvValidator\Validator\Validator;

class EnvValidatorCommandFactory
{

    public function __invoke(): EnvValidatorCommand
    {
        return new EnvValidatorCommand(
            new Validator(
                new DotEnvLoader('./'),
                new StatusFactory()
            )
        );
    }
}