<?php

namespace App\Test\Service;

use App\Contract\CountryBinResolverInterface;
use App\Contract\ExchangeRateProviderInterface;
use App\Domain\Dto;
use App\Service\CommissionCalculator;
use PHPUnit\Framework\TestCase;

class CommissionCalculatorTest extends TestCase
{
    public function testCommissionCalculator(): void
    {
        $sut = new CommissionCalculator(
            new class implements CountryBinResolverInterface {
                public function getCountryBasedOnBinNumber(string $binNumber): string
                {
                    if ($binNumber === '1234567') {
                        return 'PL';
                    }

                    throw new \RuntimeException('this should not happen');
                }
            },
            new class implements ExchangeRateProviderInterface {
                public function getExchangeRateForCurrency(string $currency): float
                {
                    if ($currency === 'PLN') {
                        return 4.3812;
                    }
                    throw new \RuntimeException('this should not happen');
                }
            },
        );

        $result = $sut->calculate(Dto::create('1234567', 100, 'PLN'));
        $this->assertEquals(0.46, $result);
    }
}
