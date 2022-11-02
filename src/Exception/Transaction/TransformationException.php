<?php

namespace App\Exception\Transaction;

use Exception;

class TransformationException extends Exception
{
    private string $serializedTransaction;

    public function __construct(string $serializedTransaction, string $message)
    {
        parent::__construct($message);
        $this->serializedTransaction = $serializedTransaction;
    }

    public function getSerializedTransaction(): string
    {
        return $this->serializedTransaction;
    }
}