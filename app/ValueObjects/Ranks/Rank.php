<?php

namespace App\ValueObjects\Ranks;

use App\ValueObjects\NumberValue;

abstract class Rank extends NumberValue
{
    public function __construct(int $value)
    {
        parent::__construct($value);
    }

    abstract function getMultiplier(): float;
}