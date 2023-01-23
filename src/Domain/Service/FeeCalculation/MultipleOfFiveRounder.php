<?php

namespace CodingTest\Interpolation\Domain\Service\FeeCalculation;

use CodingTest\Interpolation\Domain\Value\MoneyAmount;

class MultipleOfFiveRounder
{
    public function round(MoneyAmount $loanAmount, MoneyAmount $feeAmount): MoneyAmount
    {
        $requiredFee = $feeAmount->value();

        while (($loanAmount->value() + $requiredFee) % 500 !== 0) {
            $requiredFee++;
        }

        return new MoneyAmount($requiredFee);
    }
}