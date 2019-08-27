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
     * Returns the individual var results, either all of them or filtered by Status type
     *
     * @param string|null $statusType
     *
     * @return VarResponse[]
     */
    public function getVarResponseList(string $statusType = null): array
    {
        if ($statusType === null) {

            return $this->varResponseList;
        }

        return array_filter($this->varResponseList, static function (VarResponse $varResponse) use ($statusType) {
            return $varResponse->getStatus()->getType() === $statusType;
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