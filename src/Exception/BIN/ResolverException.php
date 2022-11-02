<?php

namespace App\Exception\BIN;

use Exception;

class ResolverException extends Exception
{
    private string $bin;

    public function __construct(string $bin, string $message)
    {
        parent::__construct($message);
        $this->bin = $bin;
    }

    public function getBin(): string
    {
        return $this->bin;
    }
}