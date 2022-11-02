<?php

namespace App\DTO;

use App\Contract\CountryInterface;

final class Country implements CountryInterface
{
    private string $alpha2;

    public function __construct(string $alpha2)
    {
        $this->alpha2 = $alpha2;
    }

    public function getAlpha2(): string
    {
        return $this->alpha2;
    }

    public function isEU(): bool
    {
        return in_array(
            $this->getAlpha2(),
            ['AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK',]
        );
    }
}