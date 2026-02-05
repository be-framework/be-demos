<?php

declare(strict_types=1);

namespace Be\Demo\InsuranceClaim\Input;

use Be\Demo\InsuranceClaim\Final\ClaimEscalated;
use Be\Demo\InsuranceClaim\Final\ClaimSettled;
use Be\Framework\Attribute\Be;

/**
 * Policy Input - Multiple-Input Convergence Demo
 *
 * One of two Inputs that converge into the claim processing pipeline.
 * Carries the policy holder's coverage data into the metamorphosis.
 *
 * @link https://schema.org/InsurancePolicy
 */
#[Be([ClaimSettled::class, ClaimEscalated::class])]
final readonly class PolicyInput
{
    public function __construct(
        public string $policyNumber,
        public string $policyHolderId,
        public string $coverageType
    ) {
    }
}
