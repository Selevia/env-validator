<?php


namespace Selevia\Common\EnvValidator\Validator;


use Selevia\Common\EnvValidator\Validator\Result\ValidationResult;
use Selevia\Common\EnvValidator\Validator\Result\VarResult;
use Selevia\Common\EnvValidator\Validator\Status\StatusFactory;

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
        $actualVars = $this->getLoader()->loadActualVariables();
        $expectedVars = $this->getLoader()->loadExpectedVariables();

        $result = new ValidationResult();
        foreach ($this->determineResults($actualVars, $expectedVars) as $varResult) {
            $result->addVarResult($varResult);
        }

        return $result;
    }

    /**
     * Compares the env vars and yields the result for each one
     *
     * @param string[] $actualVars
     * @param string[] $expectedVars
     *
     * @return iterable|VarResult[]
     */
    protected function determineResults(array $actualVars, array $expectedVars): iterable
    {
        $missingVars = array_diff_key($expectedVars, $actualVars);
        foreach ($missingVars as $key => $value) {
            yield new VarResult(
                $key,
                $this->getStatusFactory()->createError('Expected env var %s was completely missing')
            );
        }

        $unexpectedVars = array_diff_key($actualVars, $expectedVars);
        foreach ($unexpectedVars as $key => $value) {
            yield new VarResult(
                $key,
                $this->getStatusFactory()->createWarning('Unexpected env var %s encountered')
            );
        }

        $presentExpectedVars = array_intersect_key($actualVars, $expectedVars);
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
