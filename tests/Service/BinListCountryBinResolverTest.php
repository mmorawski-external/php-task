<?php

namespace App\Test\Service;

use App\Service\BinListCountryBinResolver;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class BinListCountryBinResolverTest extends TestCase
{
    public function testCountryUseEuro(): void
    {
        $mock = new MockHandler([
            new Response(200, body: '{"number":{},"scheme":"visa","type":"debit","brand":"Visa Classic","country":{"numeric":"208","alpha2":"DK","name":"Denmark","emoji":"🇩🇰","currency":"DKK","latitude":56,"longitude":10},"bank":{"name":"Jyske Bank A/S"}}'),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $sut = new BinListCountryBinResolver($client);

        $this->assertSame('DK', $sut->getCountryBasedOnBinNumber('12345'));
    }

    public function testCountryNotUseEuro(): void
    {
        $mock = new MockHandler([
            new Response(200, body: '{"number":{},"scheme":"visa","type":"credit","brand":"Visa Classic","country":{"numeric":"392","alpha2":"JP","name":"Japan","emoji":"🇯🇵","currency":"JPY","latitude":36,"longitude":138},"bank":{"name":"Credit Saison Co., Ltd."}}'),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $sut = new BinListCountryBinResolver($client);

        $this->assertSame('JP', $sut->getCountryBasedOnBinNumber('12345'));
    }
}
