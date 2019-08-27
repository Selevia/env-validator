<?php


namespace Selevia\Common\EnvValidator\Status;


class StatusFactory
{

    /**
     * @param string $message
     *
     * @return Status
     */
    public function createSuccess(string $message): Status
    {
        return new Status(Status::TYPE_SUCCESS, $message);
    }

    /**
     * @param string $message
     *
     * @return Status
     */
    public function createError(string $message): Status
    {
        return new Status(Status::TYPE_ERROR, $message);
    }

    /**
     * @param string $message
     *
     * @return Status
     */
    public function createWarning(string $message): Status
    {
        return new Status(Status::TYPE_WARNING, $message);
    }
}