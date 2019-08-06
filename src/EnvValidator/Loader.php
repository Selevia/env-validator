<?php


namespace Selevia\Common\EnvValidator;


interface Loader
{

    /**
     * @return array
     */
    public function loadEnvVariables(): array;

    /**
     * @return array
     */
    public function loadEnvExampleVariables(): array;
}