<?php


namespace Selevia\Common\EnvValidator\Response;


use Selevia\Common\EnvValidator\Status\Status;

class VarResponse
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Status
     */
    protected $status;

    /**
     * VarResponse constructor.
     *
     * @param string $name
     * @param Status $status
     */
    public function __construct(string $name, Status $status)
    {
        $this->name = $name;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function createMessage(): string
    {
        return sprintf($this->getStatus()->getMessage(), $this->getName());
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }
}