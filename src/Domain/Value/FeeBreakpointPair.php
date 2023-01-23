<?php

namespace CodingTest\Interpolation\Domain\Value;

final class FeeBreakpointPair
{
    public function __construct(private FeeBreakpoint $first, private FeeBreakpoint $second)
    {
    }

    public function first(): FeeBreakpoint
    {
        return $this->first;
    }

    public function second(): FeeBreakpoint
    {
        return $this->second;
    }
}