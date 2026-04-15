<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Being;

use Be\Pattern\LoanApplication\Reason\InsuranceQuoterInterface;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Insurance Quoted - Being (Stage 2 parallel branch)
 *
 * Obtains insurance quote for the loan coverage.
 */
final readonly class InsuranceQuoted
{
    public int $monthlyPremium;
    public int $coverageAmount;

    public function __construct(
        #[Input] public string $applicantId,
        #[Input] public int $requestedAmount,
        #[Inject] InsuranceQuoterInterface $quoter
    ) {
        $result = $quoter->quote($applicantId, $requestedAmount);
        $this->monthlyPremium = $result['monthlyPremium'];
        $this->coverageAmount = $result['coverageAmount'];
    }
}
