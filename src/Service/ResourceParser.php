<?php

namespace App\Service;

use App\Contract\ParserInterface;
use App\Domain\Dto;

class ResourceParser implements ParserInterface
{
    /**
     * @var resource
     */
    private $handler;

    public function __construct($handler)
    {
        if (!is_resource($handler)) {
            throw new \InvalidArgumentException(sprintf('Handler is not resource. Got "%s"', get_debug_type($handler)));
        }

        $this->handler = $handler;
    }

    public function getIterable(): iterable
    {
        while (!feof($this->handler)) {
            $line = trim(fgets($this->handler));

            if (strlen($line) == 0) {
                continue;
            }

            $array = json_decode($line, true, flags: JSON_THROW_ON_ERROR);

            // should we validate input date?
            if (empty($array['bin']) || (!isset($array['amount']) || $array['amount'] === '') || empty($array['currency'])) {
                continue;
            }

            $dto = Dto::create($array['bin'], floatval($array['amount']), $array['currency']);

            yield $dto;
        }
    }
}