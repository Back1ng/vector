<?php

namespace App\ValueObjects\Ranks;

final class MiddleRank extends Rank
{
    public function __construct()
    {
        parent::__construct(2);
    }

    public function getMultiplier(): float
    {
        return 1.25;
    }
}