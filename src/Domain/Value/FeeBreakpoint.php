<?php

namespace CodingTest\Interpolation\Domain\Value;

final class FeeBreakpoint
{
    public function __construct(private MoneyAmount $loanAmount, private MoneyAmount $feeAmount)
    {
    }

    public function loanAmount(): MoneyAmount
    {
        return $this->loanAmount;
    }

    public function feeAmount(): MoneyAmount
    {
        return $this->feeAmount;
    }
}