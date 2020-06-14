<?php


namespace Selevia\EnvValidator\Validator;


use Selevia\EnvValidator\Validator\Exception\FileNotFoundException;
use Selevia\EnvValidator\Validator\Exception\InvalidFormatException;
use Selevia\EnvValidator\Validator\Variable\VariableSet;

interface Loader
{

    /**
     * Returns the list of actual env vars
     *
     * @return VariableSet
     *
     * @throws FileNotFoundException
     * @throws InvalidFormatException
     */
    public function loadActualVariables(): VariableSet;

    /**
     * Returns the list of expected env vars
     *
     * @return VariableSet
     *
     * @throws FileNotFoundException
     * @throws InvalidFormatException
     */
    public function loadExpectedVariables(): VariableSet;
}