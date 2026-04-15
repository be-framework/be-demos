<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Moment;

use Be\Pattern\LoanApplication\Moment\Potential\InsuranceContract;
use Be\Pattern\LoanApplication\Reason\InsuranceQuoterInterface;
use Ray\Di\Di\Inject;

/**
 * Insurance Prepared - Moment (part + potential)
 *
 * Part of LoanApproved (Stage 2), holding the potential to bind insurance.
 * Born from InsuranceQuoter, carries InsuranceContract potential.
 */
final readonly class InsurancePrepared implements MomentInterface
{
    public InsuranceContract $contract;

    public function __construct(
        #[Inject] InsuranceQuoterInterface $quoter,
    ) {
        // Born: create potential (prepare insurance contract)
        $this->contract = $quoter->prepare();
    }

    public function be(): void
    {
        $this->contract->be();
    }
}
