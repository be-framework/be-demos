<?php

declare(strict_types=1);

namespace Be\Demo\InsuranceClaim\Being;

use Ray\InputQuery\Attribute\Input;
use Ray\Di\Di\Inject;
use Be\Demo\InsuranceClaim\Reason\ClaimRegistrar;

/**
 * Claim Registered - Being (first stage from ClaimInput)
 *
 * Registers the incoming claim and produces a unique claim number.
 */
final readonly class ClaimRegistered
{
    public string $claimNumber;

    public function __construct(
        #[Input] public string $claimantId,
        #[Input] public string $incidentDate,
        #[Input] public string $incidentType,
        #[Inject] ClaimRegistrar $registrar
    ) {
        $this->claimNumber = $registrar->register($claimantId, $incidentDate, $incidentType);
    }
}
