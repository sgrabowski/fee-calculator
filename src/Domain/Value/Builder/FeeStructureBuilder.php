<?php

namespace CodingTest\Interpolation\Domain\Value\Builder;

use CodingTest\Interpolation\Domain\Value\FeeBreakpoint;
use CodingTest\Interpolation\Domain\Value\Term;

final class FeeStructureBuilder
{
    private Term $currentTerm;
    private array $structure = [];

    private function __construct()
    {
    }

    public static function createNew(): self
    {
        return new self();
    }

    public function forTerm(Term $term): self
    {
        $this->currentTerm = $term;

        return $this;
    }

    public function addBreakpoint(FeeBreakpoint $breakpoint): self
    {
        if (!isset($this->currentTerm)) {
            throw new \LogicException('You must first use the "forTermDuration" method to set the term for which you are adding the breakpoint');
        }

        $currentTermDuration = $this->currentTerm->duration();

        if (!isset($this->structure[$currentTermDuration])) {
            $this->structure[$currentTermDuration] = [];
        }

        $this->structure[$currentTermDuration][] = $breakpoint;

        return $this;
    }

    public function build(): array
    {
        return $this->structure;
    }
}