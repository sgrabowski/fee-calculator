<?php

namespace CodingTest\Interpolation\Domain\Exception;

use CodingTest\Interpolation\Domain\Value\LoanApplication;

class LoanAmountException extends \DomainException
{
    public static function createForAmountTooLow(int $givenAmount): self
    {
        $message = sprintf('Requested loan amount is too low. The minimum amount for a loan is "%d", applied for "%d".', LoanApplication::AMOUNT_MINIMUM, $givenAmount);
        return new self($message);
    }

    public static function createForAmountTooHigh(int $givenAmount): self
    {
        $message = sprintf('Requested loan amount is too high. The maximum amount for a loan is "%d", applied for "%d".', LoanApplication::AMOUNT_MAXIMUM, $givenAmount);
        return new self($message);
    }
}