<?php

namespace App\DTO;

use App\Contract\BINInterface;
use App\Contract\CountryInterface;

final class BIN implements BINInterface
{
    private Country $country;

    public function __construct(Country $country)
    {
        $this->country = $country;
    }

    public function getCountry(): CountryInterface
    {
        return $this->country;
    }
}