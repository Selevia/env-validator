<?php


namespace Selevia\EnvValidator\Validator\Exception;


class FileNotFoundException extends \RuntimeException implements EnvValidatorExceptionInterface
{
    static public function fromFilename(string $filename): self
    {
        return new self("Cant find $filename in the root directory of your project");
    }
}