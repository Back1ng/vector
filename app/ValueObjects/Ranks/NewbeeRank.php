<?php

namespace App\ValueObjects\Ranks;

final class NewbeeRank extends Rank
{
    public function __construct()
    {
        parent::__construct(1);
    }

    function getMultiplier(): float
    {
        return 1;
    }
}