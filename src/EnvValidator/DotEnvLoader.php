<?php


namespace Selevia\Common\EnvValidator;


use Dotenv\Dotenv;
use Dotenv\Environment\Adapter\ArrayAdapter;
use Dotenv\Environment\DotenvFactory;

class DotEnvLoader implements Loader
{

    protected const ENV_DEFAULT_FILE = '.env';
    protected const ENV_EXAMPLE_DEFAULT_FILE = '.env.example';

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $envFile;

    /**
     * @var string
     */
    protected $envExampleFile;

    /**
     * DotEnvLoader constructor.
     *
     * @param string $path
     * @param string $envFile
     * @param string $envExamplePath
     */
    public function __construct(
        string $path,
        string $envFile = self::ENV_DEFAULT_FILE,
        string $envExamplePath = self::ENV_EXAMPLE_DEFAULT_FILE
    ) {
        $this->path = $path;
        $this->envFile = $envFile;
        $this->envExampleFile = $envExamplePath;
    }

    /**
     * @inheritdoc
     */
    public function loadEnvVariables(): array
    {
        $dotenv = Dotenv::create($this->getPath(), $this->getEnvFile(), new DotenvFactory([new ArrayAdapter()]));

        return $dotenv->load();
    }

    /**
     * @inheritdoc
     */
    public function loadEnvExampleVariables(): array
    {
        $dotenv = Dotenv::create($this->getPath(), $this->getEnvExampleFile(), new DotenvFactory([new ArrayAdapter()]));

        return $dotenv->load();
    }

    /**
     * @return string
     */
    protected function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    protected function getEnvFile(): string
    {
        return $this->envFile;
    }

    /**
     * @return string
     */
    protected function getEnvExampleFile(): string
    {
        return $this->envExampleFile;
    }

}