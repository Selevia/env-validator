<?php


namespace Selevia\Common\EnvValidator;


use Selevia\Common\EnvValidator\Result\ValidationResult;
use Selevia\Common\EnvValidator\Result\VarResult;
use Selevia\Common\EnvValidator\Status\StatusFactory;

class Validator
{

    /**
     * @var Loader
     */
    protected $loader;

    /**
     * @var StatusFactory
     */
    protected $statusFactory;

    /**
     * Validator constructor.
     *
     * @param Loader        $loader
     * @param StatusFactory $statusFactory
     */
    public function __construct(Loader $loader, StatusFactory $statusFactory)
    {
        $this->loader = $loader;
        $this->statusFactory = $statusFactory;
    }

    /**
     * Validates the loaded env vars and provides the results
     *
     * @return ValidationResult
     */
    public function validate(): ValidationResult
    {
        $envVars = $this->getLoader()->loadEnvVariables();
        $envExampleVars = $this->getLoader()->loadEnvExampleVariables();

        $result = new ValidationResult();
        foreach ($this->determineResults($envVars, $envExampleVars) as $varResult) {
            $result->addVarResult($varResult);
        }

        return $result;
    }

    /**
     * Compares the env vars and yields the result for each one
     *
     * @param string[] $envVars
     * @param string[] $envExampleVars
     *
     * @return iterable|VarResult[]
     */
    protected function determineResults(array $envVars, array $envExampleVars): iterable
    {
        $missingVars = array_diff_key($envExampleVars, $envVars);
        foreach ($missingVars as $key => $value) {
            yield new VarResult(
                $key,
                $this->getStatusFactory()->createError('Expected env var %s was completely missing')
            );
        }

        $unexpectedVars = array_diff_key($envVars, $envExampleVars);
        foreach ($unexpectedVars as $key => $value) {
            yield new VarResult(
                $key,
                $this->getStatusFactory()->createWarning('Unexpected env var %s encountered')
            );
        }

        $presentExpectedVars = array_intersect_key($envVars, $envExampleVars);
        foreach ($presentExpectedVars as $key => $value) {
            $status = empty($value)
                ? $this->getStatusFactory()->createWarning('Env var %s was present, but the value was empty')
                : $this->getStatusFactory()->createSuccess('Expected env var %s was found and had a non-empty value');

            yield new VarResult(
                $key,
                $status
            );
        }
    }

    /**
     * @return Loader
     */
    protected function getLoader(): Loader
    {
        return $this->loader;
    }

    /**
     * @return StatusFactory
     */
    protected function getStatusFactory(): StatusFactory
    {
        return $this->statusFactory;
    }
}
