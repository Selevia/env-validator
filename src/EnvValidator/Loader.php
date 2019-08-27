<?php


namespace Selevia\Common\EnvValidator;


interface Loader
{

    /**
     * Returns the list of actual env vars
     *
     * @return string[]
     */
    public function loadActualVariables(): array;

    /**
     * Returns the list of expected env vars
     *
     * @return string[]
     */
    public function loadExpectedVariables(): array;
}