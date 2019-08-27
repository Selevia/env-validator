<?php


namespace Selevia\Common\EnvValidator\Validator\Result;


use Selevia\Common\EnvValidator\Validator\Status\Status;

class VarResult
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
     * VarResult constructor.
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

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }
}