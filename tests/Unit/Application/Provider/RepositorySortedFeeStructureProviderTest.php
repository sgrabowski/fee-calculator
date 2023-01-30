<?php

namespace CodingTest\Interpolation\Tests\Unit\Application\Provider;

use CodingTest\Interpolation\Application\Provider\RepositorySortedFeeStructureProvider;
use CodingTest\Interpolation\Application\Repository\FeeStructureRepository;
use CodingTest\Interpolation\Domain\Value\Term;
use PHPUnit\Framework\TestCase;

class RepositorySortedFeeStructureProviderTest extends TestCase
{
    /**
     * @test
     */
    public function builds_sorted_fee_structure_from_raw_data(): void
    {
        $feeStructureRepository = $this->createMock(FeeStructureRepository::class);
        $feeStructureRepository->expects($this->once())->method('getFeeStructure')
            ->willReturn([
                12 => [
                    5_00 => 5,
                    1000_00 => 100
                ]
            ]);
        $provider = new RepositorySortedFeeStructureProvider($feeStructureRepository);

        $structure = $provider->provide();
        $breakpoints = $structure->breakpoints(Term::fromDuration(12));

        $this->assertCount(2, $breakpoints);
        $this->assertSame(500, $breakpoints[0]->loanAmount()->value());
        $this->assertSame(5, $breakpoints[0]->feeAmount()->value());
        $this->assertSame(100000, $breakpoints[1]->loanAmount()->value());
        $this->assertSame(100, $breakpoints[1]->feeAmount()->value());
    }

    /**
     * @test
     */
    public function throws_exception_on_incorrect_term_in_fee_structure(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Term duration of "11" months is not allowed');

        $feeStructureRepository = $this->createMock(FeeStructureRepository::class);
        $feeStructureRepository->expects($this->once())->method('getFeeStructure')
            ->willReturn([
                11 => [
                    5_00 => 5,
                    1000_00 => 100
                ]
            ]);
        $provider = new RepositorySortedFeeStructureProvider($feeStructureRepository);

        $provider->provide();
    }
}