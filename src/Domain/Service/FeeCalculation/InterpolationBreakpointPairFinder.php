<?php

namespace CodingTest\Interpolation\Domain\Service\FeeCalculation;

use CodingTest\Interpolation\Domain\Exception\IncompleteFeeStructureException;
use CodingTest\Interpolation\Domain\Value\FeeBreakpoint;
use CodingTest\Interpolation\Domain\Value\FeeBreakpointPair;
use CodingTest\Interpolation\Domain\Value\LoanApplication;
use CodingTest\Interpolation\Domain\Value\SortedFeeStructure;

class InterpolationBreakpointPairFinder
{
    public function findPair(SortedFeeStructure $feeStructure, LoanApplication $application): FeeBreakpointPair
    {
        $loanAmount = $application->amount()->value();
        $feeBreakpoints = $feeStructure->breakpoints($application->term());

        if (count($feeBreakpoints) < 2) {
            throw IncompleteFeeStructureException::createForTooFewBreakpoints($application->term()->duration());
        }

        $nearestHigherBreakpoint = null;
        $previousBreakpoint = null;

        /** @var FeeBreakpoint $breakpoint */
        foreach ($feeBreakpoints as $breakpoint) {
            //in case we find an exact match, immediately return a pair of identical breakpoints (still valid)
            if ($breakpoint->loanAmount()->value() === $loanAmount) {
                return new FeeBreakpointPair($breakpoint, $breakpoint);
            }

            if ($breakpoint->loanAmount()->value() > $loanAmount) {
                $nearestHigherBreakpoint = $breakpoint;
                break;
            }

            $previousBreakpoint = $breakpoint;
        }

        if ($nearestHigherBreakpoint === null | $previousBreakpoint === null) {
            throw IncompleteFeeStructureException::createForBreakpointsNotInCorrectRange($application->term()->duration(), $loanAmount);
        }

        return new FeeBreakpointPair($previousBreakpoint, $nearestHigherBreakpoint);
    }
}