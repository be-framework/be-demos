<?php

declare(strict_types=1);

namespace Be\Demo\InsuranceClaim\Being;

use Ray\InputQuery\Attribute\Input;
use Ray\Di\Di\Inject;
use Be\Demo\InsuranceClaim\Reason\CoverageValidator;

/**
 * Claim Validated - Being (convergence point)
 *
 * Converges data from ClaimRegistered and PolicyVerified.
 * Validates that the policy coverage applies to the incident type.
 */
final readonly class ClaimValidated
{
    public bool $coverageApplicable;
    public string $validationId;

    public function __construct(
        #[Input] public string $claimNumber,
        #[Input] public string $policyNumber,
        #[Input] public string $incidentType,
        #[Input] public string $coverageType,
        #[Inject] CoverageValidator $validator
    ) {
        $result = $validator->validate($incidentType, $coverageType);
        $this->coverageApplicable = $result['applicable'];
        $this->validationId = $result['validationId'];
    }
}
