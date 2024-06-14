<?php

namespace App\Service;

use App\Contract\CountryBinResolverInterface;
use App\Utils;
use GuzzleHttp\Client;

class BinListCountryBinResolver implements CountryBinResolverInterface
{
    private Client $client;

    public function __construct(?Client $client = null)
    {
        $this->client = $client ?? new Client(array_merge(
            Utils::getDefaultHttpClientConfiguration(),
            [
                'base_uri' => 'https://lookup.binlist.net',

            ]
        ));
    }

    public function getCountryBasedOnBinNumber(string $binNumber): string
    {
        $response = $this->client->get(sprintf('/%s', $binNumber));
        $r = json_decode($response->getBody()->getContents(), true, flags: JSON_THROW_ON_ERROR);

        $countryCode = $r['country']['alpha2'] ?? null;

        if (empty($countryCode)) {
            throw new \RuntimeException('Country not found for binNumber: ' . $binNumber);
        }

        return $countryCode;
    }
}