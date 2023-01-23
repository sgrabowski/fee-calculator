<?php

namespace CodingTest\Interpolation\Domain\Value;

use CodingTest\Interpolation\Domain\Exception\NegativeAmountException;

/**
 * Represents an amount of money. It must be always expressed in the lowest denomination of the currency
 */
final class MoneyAmount
{
    public function __construct(private int $amount)
    {
        if ($this->amount < 0) {
            throw new NegativeAmountException($this->amount);
        }
    }

    public function value(): int
    {
        return $this->amount;
    }
}