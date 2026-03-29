<?php

declare(strict_types=1);

namespace Be\Demo\LoanApplication\Being;

use Be\Demo\LoanApplication\Reason\IncomePolicy;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Income Assessed - Being (Stage 1 parallel branch)
 *
 * Assesses the applicant's income stability and debt-to-income ratio.
 */
final readonly class IncomeAssessed
{
    public float $debtToIncomeRatio;
    public string $incomeStability;

    public function __construct(
        #[Input] public int $annualIncome,
        #[Input] public int $employmentYears,
        #[Input] public int $requestedAmount,
        #[Inject] IncomePolicy $policy
    ) {
        $this->debtToIncomeRatio = $policy->calculateDti($annualIncome, $requestedAmount);
        $this->incomeStability = $policy->assessStability($employmentYears);
    }
}
