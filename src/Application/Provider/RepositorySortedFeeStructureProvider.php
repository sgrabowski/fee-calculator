<?php

namespace CodingTest\Interpolation\Application\Provider;

use CodingTest\Interpolation\Application\Repository\FeeStructureRepository;
use CodingTest\Interpolation\Domain\Service\FeeCalculation\Provider\SortedFeeStructureProvider;
use CodingTest\Interpolation\Domain\Value\Builder\FeeStructureBuilder;
use CodingTest\Interpolation\Domain\Value\FeeBreakpoint;
use CodingTest\Interpolation\Domain\Value\MoneyAmount;
use CodingTest\Interpolation\Domain\Value\SortedFeeStructure;
use CodingTest\Interpolation\Domain\Value\Term;

class RepositorySortedFeeStructureProvider implements SortedFeeStructureProvider
{
    public function __construct(private FeeStructureRepository $feeStructureRepository)
    {
    }

    public function provide(): SortedFeeStructure
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