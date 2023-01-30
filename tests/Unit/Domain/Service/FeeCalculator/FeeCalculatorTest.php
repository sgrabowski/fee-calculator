<?php

namespace CodingTest\Interpolation\Tests\Unit\Domain\Service\FeeCalculator;

use CodingTest\Interpolation\Domain\Service\FeeCalculation\FeeCalculator;
use CodingTest\Interpolation\Domain\Service\FeeCalculation\Provider\SortedFeeStructureProvider;
use CodingTest\Interpolation\Domain\Value\Builder\FeeStructureBuilder;
use CodingTest\Interpolation\Domain\Value\FeeBreakpoint;
use CodingTest\Interpolation\Domain\Value\LoanApplication;
use CodingTest\Interpolation\Domain\Value\MoneyAmount;
use CodingTest\Interpolation\Domain\Value\SortedFeeStructure;
use CodingTest\Interpolation\Domain\Value\Term;
use PHPUnit\Framework\TestCase;

class FeeCalculatorTest extends TestCase
{
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
            ->addBreakpoint(
                new FeeBreakpoint(
                    new MoneyAmount(20000_00),
                    new MoneyAmount(1000_00)
                )
            );

        $this->structure = new SortedFeeStructure($builder);
    }

    /**
     * @test
     * @dataProvider calculatorData
     */
    public function calculates_interpolated_rounded_fees(LoanApplication $loanApplication, MoneyAmount $expectedFee): void
    {
        $provider = $this->createMock(SortedFeeStructureProvider::class);
        $provider->expects($this->once())->method('provide')->willReturnCallback(function () {
            return $this->structure;
        });
        $calculator = new FeeCalculator($provider);
        $fee = $calculator->calculate($loanApplication);

        $this->assertSame($expectedFee->value(), $fee->value());
    }

    public function calculatorData(): array
    {
        return [
            [
                new LoanApplication(Term::createForOneYear(), new MoneyAmount(1000_00)),
                new MoneyAmount(100_00)
            ],
            [
                new LoanApplication(Term::createForOneYear(), new MoneyAmount(1500_00)),
                new MoneyAmount(150_00)
            ],
            [
                new LoanApplication(Term::createForOneYear(), new MoneyAmount(19000_00)),
                new MoneyAmount(960_00)
            ],
            //TODO: I think the preferable scenario would be not to round up fees if they can exceed maximum possible fee
            [
                new LoanApplication(Term::createForOneYear(), new MoneyAmount(19999_99)),
                new MoneyAmount(1000_01)
            ],
            [
                new LoanApplication(Term::createForOneYear(), new MoneyAmount(19996_01)),
                new MoneyAmount(1003_99)
            ],
            [
                new LoanApplication(Term::createForOneYear(), new MoneyAmount(20000_00)),
                new MoneyAmount(1000_00)
            ],
        ];
    }
}