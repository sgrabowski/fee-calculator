<?php

namespace CodingTest\Interpolation\Domain\Service\FeeCalculation;

use CodingTest\Interpolation\Domain\Value\LoanApplication;
use CodingTest\Interpolation\Domain\Value\MoneyAmount;
use CodingTest\Interpolation\Domain\Value\SortedFeeStructure;

class FeeCalculator
{
    private FeeInterpolator $interpolator;
    private MultipleOfFiveRounder $rounder;
    private InterpolationBreakpointPairFinder $finder;

    public function __construct()
    {
        $this->interpolator = new FeeInterpolator();
        $this->rounder = new MultipleOfFiveRounder();
        $this->finder = new InterpolationBreakpointPairFinder();
    }

    public function calculate(LoanApplication $application, SortedFeeStructure $feeStructure): MoneyAmount
    {
        $pairForInterpolation = $this->finder->findPair($feeStructure, $application);
        $fee = $this->interpolator->interpolate($pairForInterpolation, $application->amount());

        return $this->rounder->round($application->amount(), $fee);
    }
}