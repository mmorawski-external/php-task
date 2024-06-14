<?php

namespace App\Service;

use App\Contract\CountryBinResolverInterface;
use App\Contract\ExchangeRateProviderInterface;
use App\Domain\Dto;
use App\Utils;

class CommissionCalculator
{
    public function __construct(
        private readonly CountryBinResolverInterface $countryBinResolver,
        private readonly ExchangeRateProviderInterface $exchangeRateProvider,
    ) {
    }

    public function calculate(Dto $dto): float
    {
        $countryCode = $this->countryBinResolver->getCountryBasedOnBinNumber($dto->bin);
        $rate = $this->exchangeRateProvider->getExchangeRateForCurrency($dto->currency);
        $isEuro = Utils::isCountryUseEuro($countryCode);

        if ($dto->currency == 'EUR' || $rate == 0) {
            $amount = $dto->amount;
        }

        if ($dto->currency != 'EUR' || $rate > 0) {
            $amount = $dto->amount / $rate;
        }

        return self::roundUp($amount * ($isEuro ? 0.01 : 0.02), 2);
    }

    private static function roundUp(float $number)
    {
        return (ceil($number * 100) / 100);
    }
}
