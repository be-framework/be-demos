<?php

declare(strict_types=1);

namespace Be\Demo\LoanApplication\Reason;

use Be\Demo\LoanApplication\Moment\Potential\InsuranceContract;

/**
 * Insurance Quoter Interface
 *
 * Enables testability through dependency injection.
 */
interface InsuranceQuoterInterface
{
    /**
     * Get insurance quote for loan coverage
     *
     * @return array{monthlyPremium: int, coverageAmount: int}
     */
    public function quote(string $applicantId, int $requestedAmount): array;

    /**
     * Create an insurance contract Moment with potential to bind
     */
    public function prepare(): InsuranceContract;
}
