<?php

namespace App\Service\Commission;

use App\Contract\Commission\ConverterInterface;
use App\Service\ExchangeRate\ExchangeRateApi;

class Converter implements ConverterInterface
{
    private string $baseCurrencyCode;
    private ExchangeRateApi $exchanger;


    public function __construct(string $baseCurrencyCode, ExchangeRateApi $exchanger)
    {
        $this->baseCurrencyCode = $baseCurrencyCode;
        $this->exchanger = $exchanger;
    }

    public function convert(float $amount, string $currencyCode): float
    {
        if ($currencyCode === $this->baseCurrencyCode) {
            return $amount;
        }

        $rates = $this->exchanger->latest($this->baseCurrencyCode);

        return bcdiv($amount, $rates[$currencyCode]);
    }
}