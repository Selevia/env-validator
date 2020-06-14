<?php


namespace Selevia\EnvValidator\Command;


use Selevia\EnvValidator\Validator\ValidatorFactory;

class EnvValidatorCommandFactory
{

    public function __invoke(): EnvValidatorCommand
    {
        return new EnvValidatorCommand(
            new ValidatorFactory()
        );
    }
}