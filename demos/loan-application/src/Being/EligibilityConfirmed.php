<?php

declare(strict_types=1);

namespace Be\Demo\LoanApplication\Being;

use Be\Demo\LoanApplication\Moment\CreditApproved;
use Be\Demo\LoanApplication\Moment\IncomeApproved;
use Ray\Di\Di\Inject;

/**
 * Eligibility Confirmed - Being (Stage 1 convergence point)
 *
 * Two Stage 1 Moments converge here. CreditApproved's potential is realized
 * at this convergence point, confirming the credit inquiry.
 * IncomeApproved is a pure data Moment (no potential to realize).
 */
final readonly class EligibilityConfirmed
{
    public string $eligibilityId;

    public function __construct(
        #[Inject] public CreditApproved $creditApproved,
        #[Inject] public IncomeApproved $incomeApproved,
    ) {
        // Stage 1 convergence: realize CreditApproved Moment
        $this->creditApproved->be();

        $this->eligibilityId = $this->generateEligibilityId();
    }

    private function generateEligibilityId(): string
    {
        return sprintf(
            'ELIG-%s-%s',
            date('Ymd'),
            substr(md5(
                ($this->creditApproved->inquiry->getInquiryId() ?? 'pending') .
                $this->incomeApproved->debtToIncomeRatio
            ), 0, 8)
        );
    }
}
