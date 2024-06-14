<?php

namespace App\Contract;

interface ExchangeRateProviderInterface
{
    public function getExchangeRateForCurrency(string $currency): float;
}