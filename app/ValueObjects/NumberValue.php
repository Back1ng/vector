<?php

namespace App\ValueObjects;

abstract class NumberValue
{
    public function __construct(
        private readonly int $value,
    ) {

    }

    public function __invoke(): int
    {
        return $this->value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}