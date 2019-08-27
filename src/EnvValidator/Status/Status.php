<?php


namespace Selevia\Common\EnvValidator\Status;


use LogicException;

class Status
{

    public const TYPE_SUCCESS = 'Success';
    public const TYPE_WARNING = 'Warning';
    public const TYPE_ERROR = 'Error';

    protected const STATUS_LIST = [
        self::TYPE_SUCCESS,
        self::TYPE_WARNING,
        self::TYPE_ERROR,
    ];

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $message;

    public function __construct(string $type, string $message)
    {
        $this->setType($type);
        $this->message = $message;
    }

    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @throws LogicException When the status type is unrecognized
     */
    protected function setType(string $type): void
    {
        if (!in_array($type, self::STATUS_LIST, true)) {
            throw new LogicException("Unrecognized status: $type");
        }

        $this->type = $type;
    }

    /**
     * @inheritdoc
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}