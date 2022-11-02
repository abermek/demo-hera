<?php

namespace App\Contract\BIN;

use App\Contract\BINInterface;

interface ResolverInterface
{
    public function resolve(string $bin): BINInterface;
}