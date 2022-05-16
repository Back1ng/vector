<?php

use App\Exceptions\InvalidRankException;
use App\ValueObjects\Rank;
use PHPUnit\Framework\TestCase;

class RankTest extends TestCase
{
    public function testContructorReturnCorrectErrorMessage()
    {
        $this->expectExceptionMessage('Incorrect rank given. Expects from 1 to 3, but passed 5');

        new Rank(5);
    }

    /**
     * @dataProvider rankProvider
     */
    public function testCorrectRank(int $actual, bool $expected)
    {
        $rank = new Rank(1);

        $this->assertEquals($expected, $rank->isCorrectRank($actual));
    }

    public function rankProvider()
    {
        return [
            [0, false],
            [1, true],
            [2, true],
            [3, true],
            [4, false],
        ];
    }
}