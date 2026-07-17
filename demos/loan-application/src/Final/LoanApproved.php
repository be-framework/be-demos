<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Final;

use Be\Pattern\LoanApplication\Moment\CollateralValued;
use Be\Pattern\LoanApplication\Moment\InsurancePrepared;
use Be\Pattern\LoanApplication\Reason\LoanPolicy;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Loan Approved - Final (Stage 2 convergence point)
 *
 * Cascade metamorphosis: Stage 2 Moments converge into the Final.
 * CollateralValued and InsurancePrepared are realized here through self-completion.
 * Combined with eligibility data from Stage 1, the loan is approved.
 *
 * @link https://schema.org/LoanOrCredit
 */
final readonly class LoanApproved
{
    public string $loanId;
    public int $approvedAmount;
    public float $interestRate;
    public string $approvedAt;

    public function __construct(
        #[Input] public string $eligibilityId,
        #[Input] public int $requestedAmount,
        #[Inject] public CollateralValued $collateral,
        #[Inject] public InsurancePrepared $insurance,
        #[Inject] LoanPolicy $policy,
    ) {
        // Stage 2 convergence: realize all Stage 2 Moments
        $this->collateral->be();
        $this->insurance->be();

        // Calculate final loan terms using LoanPolicy
        $appraisedValue = $this->collateral->registration->appraisedValue;
        $this->approvedAmount = $policy->calculateApprovedAmount($requestedAmount, $appraisedValue);
        $this->interestRate = $policy->calculateInterestRate($requestedAmount, $appraisedValue);
        $this->approvedAt = date('Y-m-d\TH:i:sP');

        $this->loanId = $this->generateLoanId();
    }

    private function generateLoanId(): string
    {
        return sprintf(
            'LOAN-%s-%s',
            date('Ymd'),
            substr(md5(
                ($this->collateral->registration->getRegistrationId() ?? 'pending') .
                ($this->insurance->contract->getPolicyId() ?? 'pending') .
                $this->eligibilityId
            ), 0, 8)
        );
    }
}
