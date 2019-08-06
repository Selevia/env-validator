<?php


namespace Selevia\Common\EnvValidator;


use Selevia\Common\EnvValidator\Response\ValidatorResponse;
use Selevia\Common\EnvValidator\Response\VarResponse;
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
     * @return ValidatorResponse
     */
    public function execute(): ValidatorResponse
    {
        $envVars = $this->getLoader()->loadEnvVariables();
        $envExampleVars = $this->getLoader()->loadEnvExampleVariables();

        $response = new ValidatorResponse();
        foreach ($this->createVarResponses($envVars, $envExampleVars) as $varResponse) {
            $response->addVarResponse($varResponse);
        }

        return $response;
    }

    /**
     * @param array $envVars
     * @param array $envExampleVars
     *
     * @return iterable|VarResponse[]
     */
    protected function createVarResponses(array $envVars, array $envExampleVars): iterable
    {
        foreach (array_diff_key($envExampleVars, $envVars) as $key => $value) {
            yield new VarResponse(
                $key,
                $this->getStatusFactory()->createError('Expected env var %s was completely missing')
            );
        }

        foreach (array_diff_key($envVars, $envExampleVars) as $key => $value) {
            yield new VarResponse(
                $key,
                $this->getStatusFactory()->createWarning('Unexpected env var %s encountered')
            );
        }

        $expectedEnvVars = array_intersect_key($envVars, $envExampleVars);
        foreach ($expectedEnvVars as $key => $value) {
            if (empty($value)) {
                yield new VarResponse(
                    $key,
                    $this->getStatusFactory()->createWarning('Env var %s was present, but the value was empty')
                );
            } else {
                yield new VarResponse(
                    $key,
                    $this->getStatusFactory()->createSuccess('Expected env var %s was found and had a non-empty value')
                );
            }
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
