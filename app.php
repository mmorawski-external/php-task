<?php

require_once __DIR__ . '/vendor/autoload.php';

$filePath = $argv[1] ?? '';

if (!file_exists($filePath)) {
    throw new Exception("File not found: $filePath");
}

if (!is_readable($filePath)) {
    throw new Exception("File not readable: $filePath");
}

$parser = new \App\Service\ResourceParser(fopen($filePath, 'rb'));
$calculator = new \App\Service\CommissionCalculator(
    new \App\Service\BinListCountryBinResolver(),
    new \App\Service\ApiExchangeRateProvider()
);
foreach ($parser->getIterable() as $dto) {
    try {
        echo $calculator->calculate($dto);
        echo PHP_EOL;
    } catch (Throwable $e) {
        fprintf(STDERR, "%s\n", 'Error: ' . $e->getMessage());
    }
}
