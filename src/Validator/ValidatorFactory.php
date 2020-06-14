<?php


namespace Selevia\EnvValidator\Validator;


use Selevia\EnvValidator\Validator\Status\StatusFactory;

class ValidatorFactory
{

    public function createForFilenames(string $actual, string $expected): Validator
    {
        return new Validator(
            new DotEnvLoader('./', $actual, $expected),
            new StatusFactory()
        );
    }
}