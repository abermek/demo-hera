<?php

namespace App\Contract\Commission;

interface ConverterInterface
{
    public function convert(float $amount, string $currencyCode): float;
}