<?php

namespace CodingTest\Interpolation\Application\Service\FeeCalculator;

use CodingTest\Interpolation\Domain\Value\Builder\FeeStructureBuilder;
use CodingTest\Interpolation\Domain\Value\FeeBreakpoint;
use CodingTest\Interpolation\Domain\Value\LoanApplication;
use CodingTest\Interpolation\Domain\Service\FeeCalculation\FeeCalculator as DomainCalculatorService;
use CodingTest\Interpolation\Domain\Value\MoneyAmount;
use CodingTest\Interpolation\Domain\Value\SortedFeeStructure;
use CodingTest\Interpolation\Domain\Value\Term;
use CodingTest\Interpolation\Infrastructure\Repository\FeeStructure\FeeStructureRepository;

class FeeCalculator implements FeeCalculatorInterface
{
    public function __construct(
        private FeeStructureRepository $feeStructureRepository,
        private DomainCalculatorService $domainCalculator
    ) {
    }

    public function calculate(LoanApplication $application): float
    {
        $feeInLowestDenomination = $this->domainCalculator->calculate($application, $this->buildFeeStructure());

        return $feeInLowestDenomination->value() / 100;
    }

    private function buildFeeStructure(): SortedFeeStructure
    {
        $rawStructure = $this->feeStructureRepository->getFeeStructure();
        $builder = FeeStructureBuilder::createNew();

        foreach ($rawStructure as $termDuration => $breakpoints) {
            $builder->forTerm(Term::fromDuration($termDuration));

            foreach ($breakpoints as $loanAmount => $feeAmount) {
                $breakpoint = new FeeBreakpoint(
                    new MoneyAmount($loanAmount),
                    new MoneyAmount($feeAmount)
                );

                $builder->addBreakpoint($breakpoint);
            }
        }

        return new SortedFeeStructure($builder);
    }
}