<?php


namespace Selevia\Common\EnvValidator;


use Dotenv\Dotenv;
use Dotenv\Environment\Adapter\ArrayAdapter;
use Dotenv\Environment\DotenvFactory;

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
    public function loadActualVariables(): array
    {
        $dotenv = Dotenv::create($this->getPath(), $this->getEnvFileActual(), new DotenvFactory([new ArrayAdapter()]));

        return $dotenv->load();
    }

    /**
     * @inheritdoc
     */
    public function loadExpectedVariables(): array
    {
        $dotenv = Dotenv::create($this->getPath(), $this->getEnvFileExpected(), new DotenvFactory([new ArrayAdapter()]));

        return $dotenv->load();
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