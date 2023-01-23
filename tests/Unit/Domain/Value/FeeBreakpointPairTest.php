<?php

namespace CodingTest\Interpolation\Tests\Unit\Domain\Value;

use CodingTest\Interpolation\Domain\Value\FeeBreakpoint;
use CodingTest\Interpolation\Domain\Value\FeeBreakpointPair;
use CodingTest\Interpolation\Domain\Value\MoneyAmount;
use PHPUnit\Framework\TestCase;

class FeeBreakpointPairTest extends TestCase
{
    /**
     * @test
     */
    public function can_create_and_read_from(): void
    {
        $first = new FeeBreakpoint(new MoneyAmount(1), new MoneyAmount(2));
        $second = new FeeBreakpoint(new MoneyAmount(3), new MoneyAmount(4));

        $pair = new FeeBreakpointPair($first, $second);

        $this->assertSame($first, $pair->first());
        $this->assertSame($second, $pair->second());
    }
}