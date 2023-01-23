<?php

namespace CodingTest\Interpolation\Domain\Value;

use CodingTest\Interpolation\Domain\Value\Builder\FeeStructureBuilder;

final class SortedFeeStructure
{
    private array $structure = [];

    public function __construct(FeeStructureBuilder $builder)
    {
        $this->structure = $builder->build();

        //I assume the structure will stay relatively small (below 100 elements), so we won't get punished for sorting after inserting all the elements
        foreach($this->structure as $term => $breakpoints) {
            usort($breakpoints, function (FeeBreakpoint $first, FeeBreakpoint $second) {
                return $first->loanAmount() <=> $second->loanAmount();
            });

            $this->structure[$term] = $breakpoints;
        }
    }

    /**
     * @return array<FeeBreakpoint>
     */
    public function breakpoints(Term $term): array
    {
        return $this->structure[$term->duration()] ?? [];
    }
}