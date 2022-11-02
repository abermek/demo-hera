<?php

namespace App\Exception\Vendor;

use Exception;
use Throwable;

class ApiException extends Exception
{
    private string $endpoint;
    private int $httpCode;

    public function __construct(string $endpoint, string $message, int $httpCode, Throwable $origin = null)
    {
        parent::__construct($message, 0, $origin);
        $this->endpoint = $endpoint;
        $this->httpCode = $httpCode;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }
}