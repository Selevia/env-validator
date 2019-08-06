<?php


namespace Selevia\Common\Command;


use Selevia\Common\EnvValidator\DotEnvLoader;
use Selevia\Common\EnvValidator\Status\StatusFactory;
use Selevia\Common\EnvValidator\Validator;

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