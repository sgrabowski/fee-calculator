<?php

namespace CodingTest\Interpolation\Tests\Unit\Domain\Value;

use CodingTest\Interpolation\Domain\Exception\LoanAmountException;
use CodingTest\Interpolation\Domain\Value\LoanApplication;
use CodingTest\Interpolation\Domain\Value\MoneyAmount;
use CodingTest\Interpolation\Domain\Value\Term;
use PHPUnit\Framework\TestCase;

class LoanApplicationTest extends TestCase
{
    /**
     * @test
     */
    public function can_be_created_and_read_from(): void
    {
        $application = new LoanApplication(
            Term::createForOneYear(),
            new MoneyAmount(123400)
        );

        $this->assertSame(123400, $application->amount()->value());
        $this->assertSame(12, $application->term()->duration());
    }

    /**
     * @test
     */
    public function throws_exception_if_amount_is_too_low(): void
    {
        $this->expectException(LoanAmountException::class);
        $this->expectExceptionMessage('Requested loan amount is too low');

        new LoanApplication(
            Term::createForOneYear(),
            new MoneyAmount(999_99)
        );
    }

    /**
     * @test
     */
    public function throws_exception_if_amount_is_too_high(): void
    {
        $this->expectException(LoanAmountException::class);
        $this->expectExceptionMessage('Requested loan amount is too high');

        new LoanApplication(
            Term::createForOneYear(),
            new MoneyAmount(20_000_01)
        );
    }
}