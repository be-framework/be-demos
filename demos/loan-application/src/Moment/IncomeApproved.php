<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Moment;

use Be\Pattern\LoanApplication\Reason\IncomePolicy;
use Ray\Di\Di\Inject;

/**
 * Income Approved - Moment (pure data, no potential)
 *
 * Part of EligibilityConfirmed (Stage 1).
 * Unlike CreditApproved, this is a pure data Moment with no side-effect potential.
 * It holds the assessed income result for convergence.
 */
final readonly class IncomeApproved
{
    public float $debtToIncomeRatio;
    public string $incomeStability;

    public function __construct(
        #[Inject] IncomePolicy $policy,
    ) {
        // Pure data: capture income assessment result
        $result = $policy->getAssessmentResult();
        $this->debtToIncomeRatio = $result['dti'];
        $this->incomeStability = $result['stability'];
    }
}
