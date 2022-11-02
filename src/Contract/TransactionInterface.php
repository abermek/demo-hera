<?php

namespace App\Contract;

interface TransactionInterface
{
    public function getBIN(): string;
    public function getAmount(): float;
    public function getCurrency(): string;
}