<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Semantic;

use Be\Pattern\LoanApplication\Exception\InvalidLoanAmountException;
use Be\Framework\Attribute\Validate;

/**
 * Loan Amount
 *
 * @link https://schema.org/loanAmount
 */
final class LoanAmount
{
    #[Validate]
    public function validate(int $requestedAmount): void
    {
        // Minimum: 1,000,000 yen (1 million)
        if ($requestedAmount < 1000000) {
            throw new InvalidLoanAmountException('amount_too_low');
        }

        // Maximum: 200,000,000 yen (200 million)
        if ($requestedAmount > 200000000) {
            throw new InvalidLoanAmountException('amount_too_high');
        }
    }
}
