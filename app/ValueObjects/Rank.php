<?php

namespace App\ValueObjects;

use App\Exceptions\InvalidRankException;

class Rank extends NumberValue
{
    public function __construct(int $value)
    {
        if (! $this->isCorrectRank($value)) {
            throw new InvalidRankException("Incorrect rank given. Expects from 1 to 3, but passed " . $value);
        }

        parent::__construct($value);
    }

    /**
     * @param int $value
     * @return bool
     */
    public function isCorrectRank(int $value): bool
    {
        return in_array($value, range(1, 3));
    }
}