<?php

namespace App\Contract\Commission;

use App\Contract\TransactionInterface;

interface CalculatorInterface
{
    public function calculate(TransactionInterface $transaction): float;
}