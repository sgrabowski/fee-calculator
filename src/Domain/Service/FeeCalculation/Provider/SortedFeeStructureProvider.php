<?php

namespace CodingTest\Interpolation\Domain\Service\FeeCalculation\Provider;

use CodingTest\Interpolation\Domain\Value\SortedFeeStructure;

interface SortedFeeStructureProvider
{
    public function provide(): SortedFeeStructure;
}