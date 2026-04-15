<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Being;

use Be\Pattern\LoanApplication\Reason\CreditBureauInterface;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Credit Scored - Being (Stage 1 parallel branch)
 *
 * Retrieves credit score and rating from the credit bureau.
 */
final readonly class CreditScored
{
    public int $creditScore;
    public string $creditRating;

    public function __construct(
        #[Input] public string $applicantId,
        #[Inject] CreditBureauInterface $bureau
    ) {
        $result = $bureau->score($applicantId);
        $this->creditScore = $result['score'];
        $this->creditRating = $result['rating'];
    }
}
