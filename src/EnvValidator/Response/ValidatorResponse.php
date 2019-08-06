<?php


namespace Selevia\Common\EnvValidator\Response;


class ValidatorResponse
{

    /**
     * @var VarResponse[]
     */
    protected $varResponseList;

    /**
     * ValidatorResponse constructor.
     *
     * @param array $varResponseList
     */
    public function __construct(array $varResponseList = [])
    {
        $this->setVarResponseList($varResponseList);
    }


    /**
     * @param VarResponse $varResponse
     */
    public function addVarResponse(VarResponse $varResponse): void
    {
        $this->varResponseList[] = $varResponse;
    }

    /**
     * @param string|null $filter
     *
     * @return VarResponse[]
     */
    public function getVarResponseList(string $filter = null): array
    {
        if ($filter === null) {

            return $this->varResponseList;
        }

        return array_filter($this->varResponseList, static function (VarResponse $varResponse) use ($filter) {
            return $varResponse->getStatus()->getStatus() === $filter;
        });
    }

    /**
     * @param VarResponse[] $varResponseList
     */
    protected function setVarResponseList(array $varResponseList): void
    {
        $this->varResponseList = [];

        foreach ($varResponseList as $varResponse) {
            $this->addVarResponse($varResponse);
        }
    }
}