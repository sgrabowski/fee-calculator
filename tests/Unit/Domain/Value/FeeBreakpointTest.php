<?php

namespace CodingTest\Interpolation\Tests\Unit\Domain\Value;

use CodingTest\Interpolation\Domain\Value\FeeBreakpoint;
use CodingTest\Interpolation\Domain\Value\MoneyAmount;
use PHPUnit\Framework\TestCase;

class FeeBreakpointTest extends TestCase
{
    /**
     * @test
     */
    public function can_create_and_read_from(): void
    {
        $breakpoint = new FeeBreakpoint(new MoneyAmount(1), new MoneyAmount(2));

        $this->assertSame(1, $breakpoint->loanAmount()->value());
        $this->assertSame(2, $breakpoint->feeAmount()->value());
    }
}