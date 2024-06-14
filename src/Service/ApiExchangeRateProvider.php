<?php

namespace App\Service;

use App\Contract\ExchangeRateProviderInterface;
use App\Utils;
use GuzzleHttp\Client;

class ApiExchangeRateProvider implements ExchangeRateProviderInterface
{
    private Client $client;

    public function __construct(?Client $client = null)
    {
        $this->client = $client ?? new Client(array_merge(
            Utils::getDefaultHttpClientConfiguration(),
            [
                'base_uri' => 'https://open.er-api.com',

            ]
        ));
    }
    public function getExchangeRateForCurrency(string $currency): float {
        $response = $this->client->get('/v6/latest?base=EUR');
        $bodyArray = json_decode($response->getBody()->getContents(), true, flags: JSON_THROW_ON_ERROR);
        $rate = $bodyArray['rates'][$currency] ?? null;

        if ($rate === null) {
            throw new \RuntimeException(sprintf('Unable to get exchange rate for currency "%s".', $currency));
        }

        return (float)$rate;
    }
}
