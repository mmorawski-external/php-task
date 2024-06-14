<?php

namespace App;

use GuzzleHttp\RequestOptions;

class Utils
{
    public static function isCountryUseEuro(string $countryIsoCode): bool
    {
        switch(strtoupper($countryIsoCode)) {
            case 'AT':
            case 'BE':
            case 'BG':
            case 'CY':
            case 'CZ':
            case 'DE':
            case 'DK':
            case 'EE':
            case 'ES':
            case 'FI':
            case 'FR':
            case 'GR':
            case 'HR':
            case 'HU':
            case 'IE':
            case 'IT':
            case 'LT':
            case 'LU':
            case 'LV':
            case 'MT':
            case 'NL':
            case 'PO':
            case 'PT':
            case 'RO':
            case 'SE':
            case 'SI':
            case 'SK':
                return true;
            default:
                return false;
        }
    }

    public static function getDefaultHttpClientConfiguration(): array
    {
        return [
            RequestOptions::READ_TIMEOUT => 6,
            RequestOptions::CONNECT_TIMEOUT => 6,
            RequestOptions::TIMEOUT => 6,
            RequestOptions::HEADERS => [
                'Accept' => 'application/json',
            ]
        ];
    }
}