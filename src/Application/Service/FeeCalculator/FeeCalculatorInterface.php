<?php

namespace CodingTest\Interpolation\Application\Service\FeeCalculator;

use CodingTest\Interpolation\Domain\Value\LoanApplication;

interface FeeCalculatorInterface
{
    /**
     * This fee should only be used for display purposes.
     * It must never be used for any calculations.
     *
     * @return float The calculated total fee.
     */
    public function calculate(LoanApplication $application): float;
}