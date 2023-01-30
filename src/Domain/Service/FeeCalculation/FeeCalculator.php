<?php

namespace CodingTest\Interpolation\Domain\Service\FeeCalculation;

use CodingTest\Interpolation\Domain\Service\FeeCalculation\Provider\SortedFeeStructureProvider;
use CodingTest\Interpolation\Domain\Value\LoanApplication;
use CodingTest\Interpolation\Domain\Value\MoneyAmount;

class FeeCalculator
{
    private FeeInterpolator $interpolator;
    private MultipleOfFiveRounder $rounder;
    private InterpolationBreakpointPairFinder $finder;
    private SortedFeeStructureProvider $feeStructureProvider;

    public function __construct(SortedFeeStructureProvider $sortedFeeStructureProvider)
    {
        $this->interpolator = new FeeInterpolator();
        $this->rounder = new MultipleOfFiveRounder();
        $this->finder = new InterpolationBreakpointPairFinder();
        $this->feeStructureProvider = $sortedFeeStructureProvider;
    }

    public function calculate(LoanApplication $application): MoneyAmount
    {
        $feeStructure = $this->feeStructureProvider->provide();
        $pairForInterpolation = $this->finder->findPair($feeStructure, $application);
        $fee = $this->interpolator->interpolate($pairForInterpolation, $application->amount());

        return $this->rounder->round($application->amount(), $fee);
    }
}