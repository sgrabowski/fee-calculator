<?php

namespace CodingTest\Interpolation\Tests\Unit\Domain\Value;

use CodingTest\Interpolation\Domain\Value\Builder\FeeStructureBuilder;
use CodingTest\Interpolation\Domain\Value\FeeBreakpoint;
use CodingTest\Interpolation\Domain\Value\MoneyAmount;
use CodingTest\Interpolation\Domain\Value\SortedFeeStructure;
use CodingTest\Interpolation\Domain\Value\Term;
use PHPUnit\Framework\TestCase;

class SortedFeeStructureTest extends TestCase
{
    /**
     * @test
     */
    public function sorts_fee_breakpoints_ascending_by_loan_amount(): void
    {
        $breakpoint1 = new FeeBreakpoint(new MoneyAmount(1), new MoneyAmount(1));
        $breakpoint2 = new FeeBreakpoint(new MoneyAmount(50), new MoneyAmount(20));
        $breakpoint3 = new FeeBreakpoint(new MoneyAmount(10), new MoneyAmount(3));

        $builder = FeeStructureBuilder::createNew()
            ->forTerm(Term::createForOneYear())
                ->addBreakpoint($breakpoint1)
                ->addBreakpoint($breakpoint2)
                ->addBreakpoint($breakpoint3);

        $sortedStructure = new SortedFeeStructure($builder);

        $breakpoints = $sortedStructure->breakpoints(Term::createForOneYear());

        $this->assertTrue($breakpoints[0]->loanAmount()->value() < $breakpoints[1]->loanAmount()->value());
        $this->assertTrue($breakpoints[1]->loanAmount()->value() < $breakpoints[2]->loanAmount()->value());
    }

    /**
     * @test
     */
    public function returns_empty_array_if_breakpoints_are_not_defined_for_given_term(): void
    {
        $breakpoint1 = new FeeBreakpoint(new MoneyAmount(1), new MoneyAmount(1));
        $breakpoint2 = new FeeBreakpoint(new MoneyAmount(50), new MoneyAmount(20));
        $breakpoint3 = new FeeBreakpoint(new MoneyAmount(10), new MoneyAmount(3));

        $builder = FeeStructureBuilder::createNew()
            ->forTerm(Term::createForOneYear())
            ->addBreakpoint($breakpoint1)
            ->addBreakpoint($breakpoint2)
            ->addBreakpoint($breakpoint3);

        $sortedStructure = new SortedFeeStructure($builder);

        $breakpoints = $sortedStructure->breakpoints(Term::createForTwoYears());

        $this->assertSame([], $breakpoints);
    }

    /**
     * @test
     */
    public function returns_empty_array_if_builder_is_not_set_up(): void
    {
        $builder = FeeStructureBuilder::createNew();

        $sortedStructure = new SortedFeeStructure($builder);

        $breakpoints = $sortedStructure->breakpoints(Term::createForTwoYears());

        $this->assertSame([], $breakpoints);
    }
}