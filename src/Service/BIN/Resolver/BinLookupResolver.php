<?php

namespace App\Service\BIN\Resolver;

use App\Contract\BIN\ResolverInterface;
use App\Contract\BINInterface;
use App\Exception\BIN\ResolverException;
use App\Exception\Vendor\ApiException;
use App\Service\BinLookup\BinLookupApi;

class BinLookupResolver implements ResolverInterface
{
    private BinLookupApi $api;

    public function __construct(BinLookupApi $api)
    {
        $this->api = $api;
    }

    public function resolve(string $bin): BINInterface
    {
        try {
            return $this->api->lookup($bin);
        } catch (ApiException $e) {
            throw new ResolverException($bin, "BIN#$bin: " . $e->getMessage());
        }
    }
}