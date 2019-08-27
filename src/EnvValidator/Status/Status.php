<?php


namespace Selevia\Common\EnvValidator\Status;


use LogicException;

class Status
{

    public const STATUS_SUCCESS = 'Success';
    public const STATUS_WARNING = 'Warning';
    public const STATUS_ERROR = 'Error';

    protected const STATUS_LIST = [
        self::STATUS_SUCCESS,
        self::STATUS_WARNING,
        self::STATUS_ERROR,
    ];

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $message;

    public function __construct(string $status, string $message)
    {
        $this->setStatus($status);
        $this->message = $message;
    }

    /**
     * @inheritdoc
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @throws LogicException When the status type is unrecognized
     */
    protected function setStatus(string $status): void
    {
        if (!in_array($status, self::STATUS_LIST, true)) {
            throw new LogicException("Unrecognized status: $status");
        }

        $this->status = $status;
    }

    /**
     * @inheritdoc
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}