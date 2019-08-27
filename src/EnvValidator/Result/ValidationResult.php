<?php


namespace Selevia\Common\EnvValidator\Result;


use Selevia\Common\EnvValidator\Result\VarResult;

class ValidationResult
{

    /**
     * @var VarResult[]
     */
    protected $varResults;

    /**
     * ValidationResult constructor.
     *
     * @param array $varResults
     */
    public function __construct(array $varResults = [])
    {
        $this->setVarResultList($varResults);
    }


    /**
     * @param VarResult $varResult
     */
    public function addVarResult(VarResult $varResult): void
    {
        $this->varResults[] = $varResult;
    }

    /**
     * Returns the individual var results, either all of them or filtered by Status type
     *
     * @param string|null $statusType
     *
     * @return VarResult[]
     */
    public function listVarResults(string $statusType = null): array
    {
        if ($statusType === null) {

            return $this->varResults;
        }

        return array_filter($this->varResults, static function (VarResult $result) use ($statusType) {
            return $result->getStatus()->getType() === $statusType;
        });
    }

    /**
     * @param VarResult[] $varResults
     */
    protected function setVarResultList(array $varResults): void
    {
        $this->varResults = [];

        foreach ($varResults as $varResult) {
            $this->addVarResult($varResult);
        }
    }
}