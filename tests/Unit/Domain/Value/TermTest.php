<?php

namespace CodingTest\Interpolation\Tests\Unit\Domain\Value;

use CodingTest\Interpolation\Domain\Value\Term;
use PHPUnit\Framework\TestCase;

class TermTest extends TestCase
{
    /**
     * @test
     */
    public function can_be_created_using_static_methods(): void
    {
        $this->assertSame(12, Term::createForOneYear()->duration());
        $this->assertSame(24, Term::createForTwoYears()->duration());
    }

    /**
     * @test
     */
    public function can_be_created_from_valid_duration(): void
    {
        $this->assertSame(
            Term::createForOneYear()->duration(),
            Term::fromDuration(12)->duration()
        );

        $this->assertSame(
            Term::createForTwoYears()->duration(),
            Term::fromDuration(24)->duration()
        );
    }

    /**
     * @test
     * @dataProvider invalidDurations
     */
    public function cannot_be_created_for_invalid_duration(int $invalidDuration): void
    {
        $this->expectException(\LogicException::class);

        Term::fromDuration($invalidDuration);
    }

    public function invalidDurations(): array
    {
        return [[-1], [0], [13], [25]];
    }
}