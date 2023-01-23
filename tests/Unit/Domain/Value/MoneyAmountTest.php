<?php

namespace CodingTest\Interpolation\Tests\Unit\Domain\Value;

use CodingTest\Interpolation\Domain\Exception\NegativeAmountException;
use CodingTest\Interpolation\Domain\Value\MoneyAmount;
use PHPUnit\Framework\TestCase;

class MoneyAmountTest extends TestCase
{
    /**
     * @test
     */
    public function can_be_created_and_returns_amount(): void
    {
        $amount = new MoneyAmount(1234);
        $this->assertSame(1234, $amount->value());
    }

    /**
     * @test
     */
    public function cannot_be_created_for_negative_amounts(): void
    {
        $this->expectException(NegativeAmountException::class);

        new MoneyAmount(-1234);
    }
}