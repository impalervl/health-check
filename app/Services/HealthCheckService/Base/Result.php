<?php

namespace App\Services\HealthCheckService\Base;

class Result
{
    /**
     * Result statuses
     */
    public const SUCCESS = 'success';
    public const FAILED = 'failed';

    /**
     * @var string
     */
    private string $status;

    /**
     * @var string
     */
    private string $notificationMessage;

    /**
     * @param Check  $check
     */
    public function __construct(public readonly Check $check){}

    /**
     * @return bool
     */
    public function isHealthy(): bool
    {
        return $this->status == self::SUCCESS;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->notificationMessage;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->check->getName();
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    public function failed(string $message = ''): self
    {
        $this->notificationMessage = $message;

        $this->status = self::FAILED;

        return $this;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    public function ok(string $message = ''): self
    {
        $this->notificationMessage = $message;

        $this->status = self::SUCCESS;

        return $this;
    }
}
