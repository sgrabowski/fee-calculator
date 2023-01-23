<?php

namespace CodingTest\Interpolation\Tests\Unit\Domain\Service\FeeCalculator;

use CodingTest\Interpolation\Domain\Service\FeeCalculation\FeeInterpolator;
use CodingTest\Interpolation\Domain\Value\FeeBreakpoint;
use CodingTest\Interpolation\Domain\Value\FeeBreakpointPair;
use CodingTest\Interpolation\Domain\Value\MoneyAmount;
use PHPUnit\Framework\TestCase;

class FeeInterpolatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider interpolationData
     */
    public function interpolates_fees(FeeBreakpointPair $breakpointPair, MoneyAmount $loanAmount, MoneyAmount $expected): void
    {
        $interpolator = new FeeInterpolator();
        $interpolated = $interpolator->interpolate($breakpointPair, $loanAmount);

        $this->assertSame($expected->value(), $interpolated->value());
    }

    public function interpolationData(): array
    {
        return [
            [
                new FeeBreakpointPair(
                    new FeeBreakpoint(
                        new MoneyAmount(1000_00),
                        new MoneyAmount(100_00)
                    ),
                    new FeeBreakpoint(
                        new MoneyAmount(1000_00),
                        new MoneyAmount(100_00)
                    )
                ),
                new MoneyAmount(1000_00),
                new MoneyAmount(100_00)
            ],
            [
                new FeeBreakpointPair(
                    new FeeBreakpoint(
                        new MoneyAmount(1000_00),
                        new MoneyAmount(100_00)
                    ),
                    new FeeBreakpoint(
                        new MoneyAmount(2000_00),
                        new MoneyAmount(200_00)
                    )
                ),
                new MoneyAmount(1500_00),
                new MoneyAmount(150_00)
            ],
            [
                new FeeBreakpointPair(
                    new FeeBreakpoint(
                        new MoneyAmount(2000_00),
                        new MoneyAmount(200_00)
                    ),
                    new FeeBreakpoint(
                        new MoneyAmount(1000_00),
                        new MoneyAmount(100_00)
                    )
                ),
                new MoneyAmount(1500_00),
                new MoneyAmount(150_00)
            ],
            [
                new FeeBreakpointPair(
                    new FeeBreakpoint(
                        new MoneyAmount(2000_00),
                        new MoneyAmount(100_00)
                    ),
                    new FeeBreakpoint(
                        new MoneyAmount(3000_00),
                        new MoneyAmount(120_00)
                    )
                ),
                new MoneyAmount(2750_00),
                new MoneyAmount(115_00)
            ],
        ];
    }
}