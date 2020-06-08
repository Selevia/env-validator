<?php


namespace Selevia\EnvValidator\Validator;


use Selevia\EnvValidator\Validator\Result\ValidationResult;
use Selevia\EnvValidator\Validator\Result\VarResult;
use Selevia\EnvValidator\Validator\Status\StatusFactory;
use Selevia\EnvValidator\Validator\Variable\VariableSet;

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
        $actualVariableSet = $this->getLoader()->loadActualVariables();
        $expectedVariableSet = $this->getLoader()->loadExpectedVariables();

        $result = new ValidationResult();
        foreach ($this->determineResults($actualVariableSet, $expectedVariableSet) as $varResult) {
            $result->addVarResult($varResult);
        }

        return $result;
    }

    /**
     * Compares the env vars and yields the result for each one
     *
     * @param VariableSet $actualVariableSet
     * @param VariableSet $expectedVariableSet
     *
     * @return iterable|VarResult[]
     */
    protected function determineResults(VariableSet $actualVariableSet, VariableSet $expectedVariableSet): iterable
    {
        $missingVars = $expectedVariableSet->subtract($actualVariableSet);
        foreach ($missingVars->toArray() as $variable) {
            yield new VarResult(
                $variable->getName(),
                $this->getStatusFactory()->createError('Expected env var %s was completely missing')
            );
        }

        $unexpectedVars = $actualVariableSet->subtract($expectedVariableSet);
        foreach ($unexpectedVars->toArray() as $variable) {
            yield new VarResult(
                $variable->getName(),
                $this->getStatusFactory()->createWarning('Unexpected env var %s encountered')
            );
        }

        $presentExpectedVars = $actualVariableSet->intersect($expectedVariableSet);
        foreach ($presentExpectedVars->toArray() as $variable) {
            $status = $variable->isEmpty()
                ? $this->getStatusFactory()->createWarning('Env var %s was present, but the value was empty')
                : $this->getStatusFactory()->createSuccess('Expected env var %s was found and had a non-empty value');

            yield new VarResult(
                $variable->getName(),
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
