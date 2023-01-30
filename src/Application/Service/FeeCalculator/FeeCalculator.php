<?php

namespace CodingTest\Interpolation\Application\Service\FeeCalculator;

use CodingTest\Interpolation\Domain\Service\FeeCalculation\FeeCalculator as DomainCalculatorService;
use CodingTest\Interpolation\Domain\Value\LoanApplication;

class FeeCalculator implements FeeCalculatorInterface
{
    public function __construct(
        private DomainCalculatorService $domainCalculator
    ) {
    }

    public function calculate(LoanApplication $application): float
    {
        $feeInLowestDenomination = $this->domainCalculator->calculate($application);

        return $feeInLowestDenomination->value() / 100;
    }
}