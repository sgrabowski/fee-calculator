<?php

namespace CodingTest\Interpolation\Tests\Unit\Application\Service\FeeCalculator;

use CodingTest\Interpolation\Application\Service\FeeCalculator\FeeCalculator;
use CodingTest\Interpolation\Domain\Service\FeeCalculation\FeeCalculator as DomainCalculatorService;
use CodingTest\Interpolation\Domain\Value\LoanApplication;
use CodingTest\Interpolation\Domain\Value\MoneyAmount;
use CodingTest\Interpolation\Domain\Value\Term;
use CodingTest\Interpolation\Infrastructure\Repository\FeeStructure\FeeStructureRepository;
use PHPUnit\Framework\TestCase;

class FeeCalculatorTest extends TestCase
{
    private FeeStructureRepository $feeStructureRepository;
    private DomainCalculatorService $domainCalculator;
    private FeeCalculator $feeCalculator;
    private LoanApplication $loanApplication;
    private MoneyAmount $returnedFee;
    private array $rawFeeStructure;

    protected function setUp(): void
    {
        $this->feeStructureRepository = $this->createMock(FeeStructureRepository::class);
        $this->domainCalculator = $this->createMock(DomainCalculatorService::class);

        $this->feeStructureRepository->expects($this->once())->method('getFeeStructure')
            ->willReturnCallback(function () {
                return $this->rawFeeStructure;
            });

        $this->feeCalculator = new FeeCalculator($this->feeStructureRepository, $this->domainCalculator);
        $this->loanApplication = new LoanApplication(Term::createForOneYear(), new MoneyAmount(1000_00));
        $this->domainCalculator->expects($this->atMost(1))->method('calculate')->willReturnCallback(function () {
            return $this->returnedFee;
        });

        $this->rawFeeStructure = [
            12 => [
                5_00 => 5,
                1000_00 => 100
            ]
        ];
    }

    /**
     * @test
     */
    public function returns_fee_as_readable_float(): void
    {
        $this->returnedFee = new MoneyAmount(114_56);

        $fee = $this->feeCalculator->calculate($this->loanApplication);

        $this->assertSame(114.56, $fee);
    }

    /**
     * @test
     */
    public function throws_exception_on_incorrect_term_in_fee_structure(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Term duration of "11" months is not allowed');

        $this->rawFeeStructure = [
            11 => [
                5_00 => 5,
                1000_00 => 100
            ]
        ];

        $this->returnedFee = new MoneyAmount(114_56);

        $this->feeCalculator->calculate($this->loanApplication);
    }
}