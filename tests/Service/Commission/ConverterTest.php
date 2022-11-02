<?php

namespace App\Test\Service\Commission;

use App\Service\Commission\Converter;
use App\Service\ExchangeRate\ExchangeRateApi;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
{
    private string $baseCurrencyCode = 'EUR';
    /** @var ExchangeRateApi | MockObject */
    private $exchanger;

    protected function setUp(): void
    {
        $this->exchanger = $this->createMock(ExchangeRateApi::class);

        parent::setUp();
    }

    private function getSUT(): Converter
    {
        return new Converter($this->baseCurrencyCode, $this->exchanger);
    }

    /** @dataProvider convertDataProvider */
    public function testConvert(float $amount, string $currency, float $rate, string $expectedResult)
    {
        if ($currency === $this->baseCurrencyCode) {
            $this->exchanger->expects(self::never())->method('latest');
        } else {
            $this->exchanger->expects(self::once())->method('latest')->willReturn([$currency => $rate]);
        }

        self::assertEquals($expectedResult, $this->getSUT()->convert($amount, $currency));
    }

    public function convertDataProvider(): array
    {
        return [
            [100.00, $this->baseCurrencyCode, 1, '100.00'],
            [100.00, 'USD', .8, bcdiv(100.00, .8)],
        ];
    }
}