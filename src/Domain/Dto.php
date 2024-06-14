<?php

namespace App\Domain;

class Dto
{
    public readonly string $bin;
    public readonly float $amount;
    public readonly string $currency;

    public static function create(string $bin, float $amount, string $currency): self
    {
        $obj = new static();
        $obj->bin = $bin;
        $obj->amount = $amount;
        $obj->currency = $currency;

        return $obj;
    }
}
