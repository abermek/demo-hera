<?php

namespace App\Test\Service\BIN;

use App\Contract\BINInterface;
use App\Exception\BIN\ResolverException;
use App\Exception\Vendor\ApiException;
use App\Service\BIN\Resolver\BinLookupResolver;
use App\Service\BinLookup\BinLookupApi;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BinLookupResolverTest extends TestCase
{
    /** @var BinLookupApi | MockObject */
    private $api;

    protected function setUp(): void
    {
        parent::setUp();

        $this->api = $this->createMock(BinLookupApi::class);
    }

    private function getSUT(): BinLookupResolver
    {
        return new BinLookupResolver($this->api);
    }

    public function testResolve(string $number = '123')
    {
        $bin = $this->createMock(BINInterface::class);
        $this->api->expects(self::once())->method('lookup')->with($number)->willReturn($bin);

        self::assertSame($bin, $this->getSUT()->resolve($number));
    }

    public function testFailedResolve(string $number = '123')
    {
        $exception = new ApiException('https://lookup.binlist.net', 'Lookup failed', 404);
        $this->api->expects(self::once())->method('lookup')->willThrowException($exception);

        $this->expectException(ResolverException::class);
        $this->expectExceptionMessage('Lookup failed');

        $this->getSUT()->resolve($number);
    }
}