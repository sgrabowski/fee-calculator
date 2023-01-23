<?php

namespace CodingTest\Interpolation\Domain\Service\FeeCalculation;

use CodingTest\Interpolation\Domain\Value\FeeBreakpointPair;
use CodingTest\Interpolation\Domain\Value\MoneyAmount;

/**
 * @internal
 *
 * Do not use the interpolator outside this namespace. It is not guaranteed to work properly if the loan amount
 * does not sit in between given break points
 */
class FeeInterpolator
{
    public function interpolate(FeeBreakpointPair $breakpointPair, MoneyAmount $loanAmount): MoneyAmount
    {
        $first = $breakpointPair->first();
        $second = $breakpointPair->second();

        //if the values are the equal, there's nothing to interpolate, we already know the correct fee
        if ($first->loanAmount()->value() === $second->loanAmount()->value()) {
            return $first->feeAmount();
        }

        /**
         * This is the ratio of "distance" of the requested value to the first breakpoint to the total "distance" between the two breakpoints.
         *
         * For example, if the first breakpoint was 1,
         * the second breakpoint was 3,
         * and our requested value was 2,
         * this ratio would be equal to 0.5 (2 sits exactly halfway between 1 and 3)
         */
        $ratioToFirstBreakpoint = ($loanAmount->value() - $first->loanAmount()->value()) / ($second->loanAmount()->value() - $first->loanAmount()->value());
        $ratioToSecondBreakpoint = 1 - $ratioToFirstBreakpoint;

        //This interpolation result can be thought of as a weighted average between the fees
        $interpolation = $first->feeAmount()->value() * $ratioToSecondBreakpoint + $second->feeAmount()->value() * $ratioToFirstBreakpoint;

        return new MoneyAmount(ceil($interpolation));
    }
}