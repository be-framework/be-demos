<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Semantic;

use Be\Framework\Attribute\Validate;
use Be\Pattern\InsuranceClaim\Exception\InvalidClaimAmountException;

/**
 * Claim Amount
 *
 * Must be a positive integer, maximum 100,000,000.
 */
final class ClaimAmount
{
    #[Validate]
    public function validate(int $estimatedAmount): void
    {
        if ($estimatedAmount < 1) {
            throw new InvalidClaimAmountException();
        }

        if ($estimatedAmount > 100_000_000) {
            throw new InvalidClaimAmountException();
        }
    }
}
