<?php

namespace App\Test\Service\Commission;

use App\Contract\BIN\ResolverInterface;
use App\Contract\BINInterface;
use App\Contract\Commission\ConverterInterface;
use App\Contract\CountryInterface;
use App\Contract\TransactionInterface;
use App\Service\Commission\Calculator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    /** @var ConverterInterface | MockObject */
    private $converter;
    /** @var ResolverInterface | MockObject */
    private $resolver;
    private float $euFee = .01;
    private float $otherFee = .02;

    protected function setUp(): void
    {
        $this->converter = $this->createMock(ConverterInterface::class);
        $this->resolver = $this->createMock(ResolverInterface::class);

        parent::setUp();
    }

    private function getSUT(): Calculator
    {
        return new Calculator($this->converter, $this->resolver, $this->euFee, $this->otherFee);
    }

    /** @dataProvider calculatorDataProvider */
    public function testCalculator(string $binNumber, float $amount, string $currency, bool $isEu, float $expectedResult)
    {
        $transaction = $this->createMock(TransactionInterface::class);
        $transaction->expects(self::any())->method('getAmount')->willReturn($amount);
        $transaction->expects(self::any())->method('getCurrency')->willReturn($currency);
        $transaction->expects(self::any())->method('getBIN')->willReturn($binNumber);

        $country = $this->createMock(CountryInterface::class);
        $country->expects(self::any())->method('isEU')->willReturn($isEu);

        $bin = $this->createMock(BINInterface::class);
        $bin->expects(self::once())->method('getCountry')->willReturn($country);

        $this->converter->expects(self::once())->method('convert')->with($amount, $currency)->willReturn($amount);
        $this->resolver->expects(self::once())->method('resolve')->with($binNumber)->willReturn($bin);

        self::assertEquals($expectedResult, $this->getSUT()->calculate($transaction));
    }

    public function calculatorDataProvider(): array
    {
        return [
            ['45717360', 100.00, 'EUR', true, 1.0],
            ['516793', 100.00, 'EUR', false, 2.0]
        ];
    }
}