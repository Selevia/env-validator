<?php


namespace Selevia\Common\EnvValidator\Validator;


use Selevia\Common\EnvValidator\Validator\Variable\VariableSet;

interface Loader
{

    /**
     * Returns the list of actual env vars
     *
     * @return VariableSet
     */
    public function loadActualVariables(): VariableSet;

    /**
     * Returns the list of expected env vars
     *
     * @return VariableSet
     */
    public function loadExpectedVariables(): VariableSet;
}