<?php

namespace CodingTest\Interpolation\Domain\Value;

use CodingTest\Interpolation\Domain\Exception\LoanAmountException;

final class LoanApplication
{
    //£1,000
    public const AMOUNT_MINIMUM = 1_000_00;
    //£20,000
    public const AMOUNT_MAXIMUM = 20_000_00;

    public function __construct(private Term $term, private MoneyAmount $amount)
    {
        if ($this->amount->value() < self::AMOUNT_MINIMUM) {
            throw LoanAmountException::createForAmountTooLow($this->amount->value());
        }

        if ($this->amount->value() > self::AMOUNT_MAXIMUM) {
            throw LoanAmountException::createForAmountTooHigh($this->amount->value());
        }
    }

    /**
     * Term (loan duration) for this loan application
     * in number of months.
     */
    public function term(): Term
    {
        return $this->term;
    }

    /**
     * Amount requested for this loan application.
     */
    public function amount(): MoneyAmount
    {
        return $this->amount;
    }
}