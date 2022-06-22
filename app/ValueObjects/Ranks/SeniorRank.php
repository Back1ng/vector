<?php

namespace App\ValueObjects\Ranks;

final class SeniorRank extends Rank
{
    public function __construct()
    {
        parent::__construct(3);
    }

    public function getMultiplier(): float
    {
        return 1.5;
    }
}