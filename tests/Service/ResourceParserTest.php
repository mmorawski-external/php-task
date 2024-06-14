<?php

namespace App\Test\Service;

use App\Domain\Dto;
use App\Service\ResourceParser;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ResourceParserTest extends TestCase
{
    #[DataProvider('providerForTestSkipEmptyLines')]
    public function testSkipEmptyLines(string $dataAsString, int $expectedNumberOfRows): void
    {
        $handler = fopen('php://memory', 'rw+');
        fwrite($handler, $dataAsString);
        fseek($handler, 0, SEEK_SET);

        $sut = new ResourceParser($handler);
        $result = iterator_to_array($sut->getIterable());

        $this->assertCount($expectedNumberOfRows, $result);
    }

    #[DataProvider('providerForTestDto')]
    public function testGeneratedDto(string $dataAsString, Dto $expectedDto): void
    {
        $handler = fopen('php://memory', 'rw+');
        fwrite($handler, $dataAsString);
        fseek($handler, 0, SEEK_SET);

        $sut = new ResourceParser($handler);
        $result = iterator_to_array($sut->getIterable());

        $this->assertCount(1, $result);
        $this->assertEquals($expectedDto, $result[0]);
    }

    #[DataProvider('providerForMalformedData')]
    public function testSkipMalformedRow(string $dataAsString, int $expectedNumberOfRows): void
    {
        $handler = fopen('php://memory', 'rw+');
        fwrite($handler, $dataAsString);
        fseek($handler, 0, SEEK_SET);

        $sut = new ResourceParser($handler);
        $result = iterator_to_array($sut->getIterable());

        $this->assertCount($expectedNumberOfRows, $result);
    }

    public static function providerForTestSkipEmptyLines(): iterable
    {
        $data = [
            '{"bin":"45717360","amount":"100.00","currency":"EUR"}',
            '',
        ];
        yield 'skip_empty_line' => [implode("\n", $data), 1];

        $data = [
            '{"bin":"45717360","amount":"100.00","currency":"EUR"}',
            '',
            '{"bin":"45417360","amount":"10000.00","currency":"JPY"}',
        ];
        yield 'skip_empty_line2' => [implode("\n", $data), 2];
    }

    public static function providerForTestDto(): iterable
    {
        $data = [
            '{"bin":"45717360","amount":"100.00","currency":"EUR"}',
            '',
        ];
        yield 'skip_empty_line' => [implode("\n", $data), Dto::create('45717360', '100.00', 'EUR')];
    }

    public static function providerForMalformedData(): iterable
    {
        $data = [
            '{"bin":"45717360","amount":"100.00","currency":"EUR"}',
            '',
            '{"bin2":"45417360","amount":"10000.00","currency":"JPY"}',
            '{"bin":"45417360","amount2":"10000.00","currency":"JPY"}',
            '{"bin":"45417360","amount":"10000.00","currency2":"JPY"}',
        ];
        yield 'skip_empty_line2' => [implode("\n", $data), 1];
    }
}
