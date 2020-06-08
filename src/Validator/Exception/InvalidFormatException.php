<?php


namespace Selevia\EnvValidator\Validator\Exception;


class InvalidFormatException extends \RuntimeException implements EnvValidatorExceptionInterface
{
    static public function fromFilename(string $filename): self
    {
        return new self("Cant recognize format of $filename");
    }
}