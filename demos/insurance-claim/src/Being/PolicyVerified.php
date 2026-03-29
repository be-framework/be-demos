<?php

declare(strict_types=1);

namespace Be\Demo\InsuranceClaim\Being;

use Ray\InputQuery\Attribute\Input;
use Ray\Di\Di\Inject;
use Be\Demo\InsuranceClaim\Reason\PolicyRegistryInterface;

/**
 * Policy Verified - Being (first stage from PolicyInput)
 *
 * Verifies the policy is active and retrieves its expiry date.
 */
final readonly class PolicyVerified
{
    public string $policyStatus;
    public string $expiryDate;

    public function __construct(
        #[Input] public string $policyNumber,
        #[Input] public string $policyHolderId,
        #[Inject] PolicyRegistryInterface $registry
    ) {
        $result = $registry->verify($policyNumber, $policyHolderId);
        $this->policyStatus = $result['status'];
        $this->expiryDate = $result['expiryDate'];
    }
}
