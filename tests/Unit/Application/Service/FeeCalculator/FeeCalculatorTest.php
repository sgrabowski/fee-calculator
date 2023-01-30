<?php

namespace CodingTest\Interpolation\Tests\Unit\Application\Service\FeeCalculator;

use CodingTest\Interpolation\Application\Service\FeeCalculator\FeeCalculator;
use CodingTest\Interpolation\Domain\Service\FeeCalculation\FeeCalculator as DomainCalculatorService;
use CodingTest\Interpolation\Domain\Value\LoanApplication;
use CodingTest\Interpolation\Domain\Value\MoneyAmount;
use CodingTest\Interpolation\Domain\Value\Term;
use PHPUnit\Framework\TestCase;

class FeeCalculatorTest extends TestCase
{
    private FeeCalculator $feeCalculator;
    private LoanApplication $loanApplication;
    private MoneyAmount $returnedFee;

    protected function setUp(): void
    {
        $domainCalculator = $this->createMock(DomainCalculatorService::class);

        $this->feeCalculator = new FeeCalculator($domainCalculator);
        $this->loanApplication = new LoanApplication(Term::createForOneYear(), new MoneyAmount(1000_00));
        $domainCalculator->expects($this->once())->method('calculate')->willReturnCallback(function () {
            return $this->returnedFee;
        });
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
}