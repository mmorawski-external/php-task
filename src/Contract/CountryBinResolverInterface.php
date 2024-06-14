<?php

namespace App\Contract;

interface CountryBinResolverInterface
{
    public function getCountryBasedOnBinNumber(string $binNumber): string;
}
