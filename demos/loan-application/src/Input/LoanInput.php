<?php

declare(strict_types=1);

namespace Be\Demo\LoanApplication\Input;

use Be\Demo\LoanApplication\Final\LoanApproved;
use Be\Framework\Attribute\Be;

/**
 * Loan Input - Cascade Metamorphosis Demo (Two-Stage Diamond)
 *
 * @link https://schema.org/LoanOrCredit
 */
#[Be([LoanApproved::class])]
final readonly class LoanInput
{
    public function __construct(
        public string $applicantId,
        public int $annualIncome,
        public int $employmentYears,
        public int $requestedAmount,
        public string $propertyAddress,
        public string $propertyType
    ) {
    }
}
