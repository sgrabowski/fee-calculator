Coding Test - Fee Calculation
=====

## Background

This test is designed to evaluate problem-solving approach and engineering ability.
The solution should be designed in a way that shows knowledge of OOP concepts, SOLID principles, design patterns, clean and extensible architecture.

## The test

The requirement is to build a fee calculator that - given a monetary **amount** and a **term** (the contractual duration of the loan, expressed as a number of months) - will produce an appropriate fee for a loan, based on a fee structure and a set of rules described below.

## Requirements

- The fee structure does not follow a formula.
- Values in between the breakpoints should be interpolated linearly between the lower bound and upper bound that they fall between.
- The number of breakpoints, their values, or storage might change.
- The term can be either 12 or 24 (number of months), you can also assume values will always be within this set.
- The fee should be rounded up such that fee + loan amount is an exact multiple of 5.
- The minimum amount for a loan is £1,000, and the maximum is £20,000.
- Values will always be within this range but they may be any value up to 2 decimal places.

## Example inputs/outputs:

| Loan amount | Term      | Fee  |
|-------------|-----------|------|
| £11,500     | 24 months | £460 |
| £19,250     | 12 months | £385 |

# Installation
A database or any other external dependency is not required for this test.

```bash
composer install
```

# Example
```php
<?php

$calculator = new FeeCalculator();

$application = new LoanApplication(24, 2750);
$fee = $calculator->calculate($application);
// $fee = (float) 115.0
```

# Fee Structure
The fee structure doesn't follow particular algorithm and it is possible that same fee will be applicable for different amounts.

### 12 months term
```
£1000: £50
£2000: £90
£3000: £90
£4000: £115
£5000: £100
£6000: £120
£7000: £140
£8000: £160
£9000: £180
£10000: £200
£11000: £220
£12000: £240
£13000: £260
£14000: £280
£15000: £300
£16000: £320
£17000: £340
£18000: £360
£19000: £380
£20000: £400
```

### 24 months term

```
£1000: £70
£2000: £100
£3000: £120
£4000: £160
£5000: £200
£6000: £240
£7000: £280
£8000: £320
£9000: £360
£10000: £400
£11000: £440
£12000: £480
£13000: £520
£14000: £560
£15000: £600
£16000: £640
£17000: £680
£18000: £720
£19000: £760
£20000: £800
```

Solution commentary
=====

## How to run
- `composer install` (the only dependency is for phpunit)
- `vendor/bin/phpunit tests/`

## Structure overview
- I decided to go with hexagonal architecture, to be able to clearly distinguish between domain business logic and the service required by the application layer
- The application service (FeeCalculator) uses domain logic to calculate the fee according to the fee structure provided by the infrastructure layer (this can easily be swapped to a DB or any other source)
- Whenever possible, the lowest currency denomination (as integer) is used for calculations to avoid any accuracy losses incurred by using floats
- Float is still used in the application service as required by the interface. This *should* be fine to display the currency in human readable form
- Domain logic for calculation, interpolation and rounding has been separated into specific classes for readability and easier testing

## Why are there so few interfaces?
- There can only be one domain `FeeCalculator` - such an important business logic cannot be left out to pick and choose the implementation. It also helps to have only one entry point to the calculation in case we need to change the logic or add some more complex strategies to the calculator (i.e. interpolation or extrapolation depending on whether we have the necessary breakpoints available)
- Fee estimators might not all fit into one interface (extrapolation would need additional arguments)
- Rounding is a very specific business requirement and also does not need to be easily swapped out
- The application `FeeCalculator` service could potentially live without an interface as well, but it was left in to make sure it adheres to the requirements
- Value objects don't require interfaces
- Fee structure repository has an interface, so we don't depend on a specific source for this data

## Possible improvements and other considerations
- There can always be more tests, e.g. repository tests
- Additional error handling and exception management would be necessary