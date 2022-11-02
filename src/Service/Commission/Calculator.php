<?php

namespace App\Service\Commission;

use App\Contract\BIN\ResolverInterface;
use App\Contract\Commission\CalculatorInterface;
use App\Contract\Commission\ConverterInterface;
use App\Contract\TransactionInterface;

class Calculator implements CalculatorInterface
{
    private ConverterInterface $converter;
    private ResolverInterface $binResolver;
    private float $euFee;
    private float $fee;

    public function __construct(ConverterInterface $converter, ResolverInterface $binResolver, float $euFee, float $fee)
    {
        $this->converter = $converter;
        $this->binResolver = $binResolver;
        $this->euFee = $euFee;
        $this->fee = $fee;
    }

    public function calculate(TransactionInterface $transaction): float
    {
        $amount = $this->converter->convert($transaction->getAmount(), $transaction->getCurrency());
        $bin = $this->binResolver->resolve($transaction->getBIN());
        $fee = $bin->getCountry()->isEU() ? $this->euFee : $this->fee;

        return bcmul($amount, $fee);
    }
}