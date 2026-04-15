<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Input;

use Be\Pattern\InsuranceClaim\Final\ClaimEscalated;
use Be\Pattern\InsuranceClaim\Final\ClaimSettled;
use Be\Framework\Attribute\Be;

/**
 * Claim Input - Multiple-Input Convergence Demo
 *
 * One of two Inputs that converge into the claim processing pipeline.
 * Carries the claimant's incident data into the metamorphosis.
 *
 * @link https://schema.org/InsuranceClaim
 */
#[Be([ClaimSettled::class, ClaimEscalated::class])]
final readonly class ClaimInput
{
    public function __construct(
        public string $claimantId,
        public string $incidentDate,
        public string $incidentType,
        public string $description,
        public int $estimatedAmount
    ) {
    }
}
