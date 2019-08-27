<?php


namespace Selevia\Common\EnvValidator\Validator;


use Doctrine\Common\Collections\ArrayCollection;
use Dotenv\Dotenv;
use Dotenv\Environment\Adapter\ArrayAdapter;
use Dotenv\Environment\DotenvFactory;
use Selevia\Common\EnvValidator\Validator\Variable\Variable;
use Selevia\Common\EnvValidator\Validator\Variable\VariableSet;

class DotEnvLoader implements Loader
{

    protected const DEFAULT_ACTUAL_ENV_FILE = '.env';
    protected const DEFAULT_EXPECTED_ENV_FILE = '.env.example';

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $envFileActual;

    /**
     * @var string
     */
    protected $envFileExpected;

    /**
     * DotEnvLoader constructor.
     *
     * @param string $path
     * @param string $envFileActual
     * @param string $envFileExpected
     */
    public function __construct(
        string $path,
        string $envFileActual = self::DEFAULT_ACTUAL_ENV_FILE,
        string $envFileExpected = self::DEFAULT_EXPECTED_ENV_FILE
    ) {
        $this->path = $path;
        $this->envFileActual = $envFileActual;
        $this->envFileExpected = $envFileExpected;
    }

    /**
     * @inheritdoc
     */
    public function loadActualVariables(): VariableSet
    {
        $dotenv = Dotenv::create($this->getPath(), $this->getEnvFileActual(), new DotenvFactory([new ArrayAdapter()]));

        return $this->createVariableSet($dotenv);
    }

    /**
     * @inheritdoc
     */
    public function loadExpectedVariables(): VariableSet
    {
        $dotenv = Dotenv::create($this->getPath(), $this->getEnvFileExpected(), new DotenvFactory([new ArrayAdapter()]));

        return $this->createVariableSet($dotenv);
    }

    /**
     * Create VariableSet for given Dotenv variables
     *
     * @param Dotenv $dotenv
     *
     * @return VariableSet
     */
    protected function createVariableSet(Dotenv $dotenv): VariableSet
    {
        $rawVariables = $dotenv->load();

        $variableList = [];
        foreach ($rawVariables as $name => $value) {
            $variableList[] = new Variable($name, $value);
        }

        return new VariableSet(new ArrayCollection($variableList));
    }

    protected function getPath(): string
    {
        return $this->path;
    }

    protected function getEnvFileActual(): string
    {
        return $this->envFileActual;
    }

    protected function getEnvFileExpected(): string
    {
        return $this->envFileExpected;
    }

}