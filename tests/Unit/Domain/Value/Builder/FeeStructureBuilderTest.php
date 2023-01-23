<?php

namespace CodingTest\Interpolation\Tests\Unit\Domain\Value\Builder;

use CodingTest\Interpolation\Domain\Value\Builder\FeeStructureBuilder;
use CodingTest\Interpolation\Domain\Value\FeeBreakpoint;
use CodingTest\Interpolation\Domain\Value\MoneyAmount;
use CodingTest\Interpolation\Domain\Value\Term;
use PHPUnit\Framework\TestCase;

class FeeStructureBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function adding_breakpoint_without_setting_term_throws_exception(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('You must first use the "forTermDuration" method');

        FeeStructureBuilder::createNew()->addBreakpoint(new FeeBreakpoint(new MoneyAmount(1), new MoneyAmount(1)));
    }

    /**
     * @test
     */
    public function builds_array_with_term_durations_and_breakpoints(): void
    {
        $breakpoint1 = new FeeBreakpoint(new MoneyAmount(1), new MoneyAmount(1));
        $breakpoint2 = new FeeBreakpoint(new MoneyAmount(5), new MoneyAmount(2));
        $breakpoint3 = new FeeBreakpoint(new MoneyAmount(10), new MoneyAmount(3));

        $expected = [
            12 => [
                $breakpoint1,
                $breakpoint2,
                $breakpoint3
            ],
            24 => [
                $breakpoint1,
                $breakpoint2
            ]
        ];

        $built = FeeStructureBuilder::createNew()
            ->forTerm(Term::createForOneYear())
                ->addBreakpoint($breakpoint1)
                ->addBreakpoint($breakpoint2)
                ->addBreakpoint($breakpoint3)
            ->forTerm(Term::createForTwoYears())
                ->addBreakpoint($breakpoint1)
                ->addBreakpoint($breakpoint2)
            ->build();

        $this->assertSame($expected, $built);
    }
}