<?php

namespace App\Contract;

interface CountryInterface
{
    public function getAlpha2(): string;

    public function isEU(): bool;
}