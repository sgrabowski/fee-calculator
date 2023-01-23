<?php

namespace CodingTest\Interpolation\Domain\Exception;

class IncompleteFeeStructureException extends \DomainException
{
    public static function createForTooFewBreakpoints(int $term): self
    {
        $message = sprintf('Fee structure is missing for term "%s". At least 2 breakpoints need to be provided', $term);
        return new self($message);
    }

    public static function createForBreakpointsNotInCorrectRange(int $term, int $amount): self
    {
        $message = sprintf('Fee structure is incomplete for term "%s". Amount "%s" is out of the breakpoint bounds', $term, $amount);
        return new self($message);
    }
}