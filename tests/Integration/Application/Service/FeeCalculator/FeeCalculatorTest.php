<?php

namespace CodingTest\Interpolation\Tests\Integration\Application\Service\FeeCalculator;

use CodingTest\Interpolation\Application\Provider\RepositorySortedFeeStructureProvider;
use CodingTest\Interpolation\Application\Service\FeeCalculator\FeeCalculator;
use CodingTest\Interpolation\Domain\Service\FeeCalculation\FeeCalculator as DomainCalculatorService;
use CodingTest\Interpolation\Domain\Value\LoanApplication;
use CodingTest\Interpolation\Domain\Value\MoneyAmount;
use CodingTest\Interpolation\Domain\Value\Term;
use CodingTest\Interpolation\Infrastructure\Repository\FeeStructure\InMemoryFeeStructureRepository;
use PHPUnit\Framework\TestCase;

class FeeCalculatorTest extends TestCase
{
    private FeeCalculator $calculator;

    protected function setUp(): void
    {
        $feeStructureRepository = new InMemoryFeeStructureRepository();
        $feeStructureProvider = new RepositorySortedFeeStructureProvider($feeStructureRepository);
        $domainCalculator = new DomainCalculatorService($feeStructureProvider);

        $this->calculator = new FeeCalculator($domainCalculator);
    }

    /**
     * @test
     * @dataProvider calculatorData
     */
    public function calculates_fees_based_on_provided_structure_and_domain_logic(int $loanAmount, int $term, float $expectedFee): void
    {
        $fee = $this->calculator->calculate(new LoanApplication(Term::fromDuration($term), new MoneyAmount($loanAmount)));

        $this->assertSame($expectedFee, $fee);
    }

    public function calculatorData(): array
    {
        return [
            [11500_00, 24, 460.00],
            [19250_00, 12, 385.00]
        ];
    }
}