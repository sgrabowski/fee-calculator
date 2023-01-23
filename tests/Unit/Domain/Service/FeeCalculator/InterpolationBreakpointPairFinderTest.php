<?php

namespace CodingTest\Interpolation\Tests\Unit\Domain\Service\FeeCalculator;

use CodingTest\Interpolation\Domain\Exception\IncompleteFeeStructureException;
use CodingTest\Interpolation\Domain\Service\FeeCalculation\InterpolationBreakpointPairFinder;
use CodingTest\Interpolation\Domain\Value\Builder\FeeStructureBuilder;
use CodingTest\Interpolation\Domain\Value\FeeBreakpoint;
use CodingTest\Interpolation\Domain\Value\LoanApplication;
use CodingTest\Interpolation\Domain\Value\MoneyAmount;
use CodingTest\Interpolation\Domain\Value\SortedFeeStructure;
use CodingTest\Interpolation\Domain\Value\Term;
use PHPUnit\Framework\TestCase;

class InterpolationBreakpointPairFinderTest extends TestCase
{
    private LoanApplication $loanApplication;
    private SortedFeeStructure $structure;

    protected function setUp(): void
    {
        $this->loanApplication = new LoanApplication(Term::createForOneYear(), new MoneyAmount(1500_00));

        $builder = FeeStructureBuilder::createNew()
            ->forTerm(Term::createForOneYear())
            ->addBreakpoint(
                new FeeBreakpoint(
                    new MoneyAmount(1000_00),
                    new MoneyAmount(100_00)
                )
            )
            ->addBreakpoint(
                new FeeBreakpoint(
                    new MoneyAmount(2000_00),
                    new MoneyAmount(200_00)
                )
            )
            ->forTerm(Term::createForTwoYears())
            ->addBreakpoint(
                new FeeBreakpoint(
                    new MoneyAmount(1000_00),
                    new MoneyAmount(100_00)
                )
            );

        $this->structure = new SortedFeeStructure($builder);
    }

    /**
     * @test
     */
    public function throws_exception_if_not_enough_breakpoints_available(): void
    {
        $this->expectException(IncompleteFeeStructureException::class);
        $this->expectExceptionMessage('Fee structure is missing');

        $finder = new InterpolationBreakpointPairFinder();
        $finder->findPair($this->structure, new LoanApplication(Term::createForTwoYears(), new MoneyAmount(1500_00)));
    }

    /**
     * @test
     */
    public function throws_exception_if_loan_amount_is_out_of_breakpoint_boundaries(): void
    {
        $this->expectException(IncompleteFeeStructureException::class);
        $this->expectExceptionMessage('Fee structure is incomplete');

        $finder = new InterpolationBreakpointPairFinder();
        $finder->findPair($this->structure, new LoanApplication(Term::createForOneYear(), new MoneyAmount(3000_00)));
    }

    /**
     * @test
     */
    public function returns_identical_pair_on_exact_match(): void
    {
        $finder = new InterpolationBreakpointPairFinder();
        $pair = $finder->findPair($this->structure, new LoanApplication(Term::createForOneYear(), new MoneyAmount(1000_00)));

        $this->assertSame(1000_00, $pair->first()->loanAmount()->value());
        $this->assertSame(100_00, $pair->first()->feeAmount()->value());
        $this->assertSame(1000_00, $pair->second()->loanAmount()->value());
        $this->assertSame(100_00, $pair->second()->feeAmount()->value());
    }

    /**
     * @test
     */
    public function finds_higher_and_lower_breakpoints_for_load_amount(): void
    {
        $finder = new InterpolationBreakpointPairFinder();
        $pair = $finder->findPair($this->structure, $this->loanApplication);

        $this->assertSame(1000_00, $pair->first()->loanAmount()->value());
        $this->assertSame(100_00, $pair->first()->feeAmount()->value());
        $this->assertSame(2000_00, $pair->second()->loanAmount()->value());
        $this->assertSame(200_00, $pair->second()->feeAmount()->value());
    }
}