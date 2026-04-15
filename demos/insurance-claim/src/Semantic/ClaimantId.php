<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Semantic;

use Be\Framework\Attribute\Validate;
use Be\Pattern\InsuranceClaim\Exception\InvalidClaimantIdException;

/**
 * Claimant ID
 *
 * Format: CLM- followed by alphanumeric characters (e.g., CLM-001)
 */
final class ClaimantId
{
    #[Validate]
    public function validate(string $claimantId): void
    {
        if (!preg_match('/^CLM-[A-Za-z0-9]+$/', $claimantId)) {
            throw new InvalidClaimantIdException();
        }
    }
}
