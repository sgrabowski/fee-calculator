<?php

namespace CodingTest\Interpolation\Domain\Value;

final class Term
{
    private const DURATION_ONE_YEAR = 12;
    private const DURATION_TWO_YEARS = 24;

    private function __construct(private int $duration)
    {
    }

    public function duration(): int
    {
        return $this->duration;
    }

    public static function createForOneYear(): self
    {
        return new self(self::DURATION_ONE_YEAR);
    }

    public static function createForTwoYears(): self
    {
        return new self(self::DURATION_TWO_YEARS);
    }

    public static function fromDuration(int $duration): self
    {
        if (!in_array($duration, [self::DURATION_ONE_YEAR, self::DURATION_TWO_YEARS])) {
            throw new \LogicException(sprintf('Term duration of "%s" months is not allowed', $duration));
        }

        return new self($duration);
    }
}