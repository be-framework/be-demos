<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Semantic;

use Be\Pattern\LoanApplication\Exception\InvalidIncomeException;
use Be\Framework\Attribute\Validate;

/**
 * Annual Income
 *
 * @link https://schema.org/annualIncome
 */
final class AnnualIncome
{
    #[Validate]
    public function validate(int $annualIncome): void
    {
        if ($annualIncome < 1) {
            throw new InvalidIncomeException('income_not_positive');
        }

        // Maximum: 1 billion yen
        if ($annualIncome > 1000000000) {
            throw new InvalidIncomeException('income_too_high');
        }
    }
}
