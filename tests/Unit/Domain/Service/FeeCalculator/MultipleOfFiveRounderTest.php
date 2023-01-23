<?php

namespace CodingTest\Interpolation\Tests\Unit\Domain\Service\FeeCalculator;

use CodingTest\Interpolation\Domain\Service\FeeCalculation\MultipleOfFiveRounder;
use CodingTest\Interpolation\Domain\Value\MoneyAmount;
use PHPUnit\Framework\TestCase;

class MultipleOfFiveRounderTest extends TestCase
{
    /**
     * @test
     * @dataProvider amountProvider
     */
    public function rounds_up_to_the_nearest_multiplication_of_500_base_units(MoneyAmount $loanAmount, MoneyAmount $feeAmount, MoneyAmount $expected): void
    {
        $rounder = new MultipleOfFiveRounder();

        $this->assertSame($expected->value(), $rounder->round($loanAmount, $feeAmount)->value());
    }

    public function amountProvider(): array
    {
        return [
            [
                //loan: 104, fee: 1, expected fee after rounding: 1
                new MoneyAmount(104_00),
                new MoneyAmount(1_00),
                new MoneyAmount(1_00)
            ],
            [
                //loan: 105, fee: 1, expected fee after rounding: 5
                new MoneyAmount(105_00),
                new MoneyAmount(1_00),
                new MoneyAmount(5_00)
            ],
            [
                //loan: 104, fee: 0, expected fee after rounding: 1
                new MoneyAmount(104_00),
                new MoneyAmount(0),
                new MoneyAmount(1_00)
            ],
            [
                //loan: 105, fee: 0, expected fee after rounding: 0
                new MoneyAmount(105_00),
                new MoneyAmount(0),
                new MoneyAmount(0)
            ],
            [
                //loan: 108, fee: 3, expected fee after rounding: 7
                new MoneyAmount(108_00),
                new MoneyAmount(3_00),
                new MoneyAmount(7_00)
            ],
            [
                //loan: 99.99, fee: 10, expected fee after rounding: 10.01
                new MoneyAmount(99_99),
                new MoneyAmount(10_00),
                new MoneyAmount(10_01)
            ],
        ];
    }
}