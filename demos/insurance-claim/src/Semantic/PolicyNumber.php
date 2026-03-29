<?php

declare(strict_types=1);

namespace Be\Demo\InsuranceClaim\Semantic;

use Be\Framework\Attribute\Validate;
use Be\Demo\InsuranceClaim\Exception\InvalidPolicyNumberException;

/**
 * Policy Number
 *
 * Format: PLY- followed by digits (e.g., PLY-123456)
 */
final class PolicyNumber
{
    #[Validate]
    public function validate(string $policyNumber): void
    {
        if (!preg_match('/^PLY-\d+$/', $policyNumber)) {
            throw new InvalidPolicyNumberException();
        }
    }
}
