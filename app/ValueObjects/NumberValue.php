<?php

namespace App\ValueObjects;

abstract class NumberValue
{
    public function __construct(
        private int $value,
    ) {

    }

    public function __invoke(): int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        return new $this(
            value: $value
        );
    }

    public function getValue(): int
    {
        return $this->value;
    }
}