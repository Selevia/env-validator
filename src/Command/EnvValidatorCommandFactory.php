<?php


namespace Selevia\Common\EnvValidator\Command;


use Selevia\Common\EnvValidator\Validator\DotEnvLoader;
use Selevia\Common\EnvValidator\Validator\Status\StatusFactory;
use Selevia\Common\EnvValidator\Validator\Validator;

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