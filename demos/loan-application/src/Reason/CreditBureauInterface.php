<?php

declare(strict_types=1);

namespace Be\Demo\LoanApplication\Reason;

use Be\Demo\LoanApplication\Moment\Potential\CreditInquiry;

/**
 * Credit Bureau Interface
 *
 * Enables testability through dependency injection.
 */
interface CreditBureauInterface
{
    /**
     * Score the applicant's creditworthiness
     *
     * @return array{score: int, rating: string}
     */
    public function score(string $applicantId): array;

    /**
     * Create a credit inquiry Moment with potential to finalize
     */
    public function inquire(): CreditInquiry;
}
