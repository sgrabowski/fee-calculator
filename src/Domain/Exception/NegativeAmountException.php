<?php

namespace CodingTest\Interpolation\Domain\Exception;

use CodingTest\Interpolation\Domain\Value\MoneyAmount;

class NegativeAmountException extends \DomainException
{
    public function __construct(int $givenAmount)
    {
        $message = sprintf('Amount given in %s must be positive. Given value: "%s"', MoneyAmount::class, $givenAmount);
        parent::__construct($message);
    }
}