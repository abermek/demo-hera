<?php

namespace App\Contract\ExchangeRate;

use App\DTO\Transaction;

interface ProviderInterface
{
    public function getRate(Transaction $transaction);
}