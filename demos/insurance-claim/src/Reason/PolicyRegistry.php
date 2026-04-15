<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Reason;

/**
 * Policy Registry - Reason (stateless gateway)
 *
 * Verifies policy status and retrieves expiry information.
 */
final class PolicyRegistry implements PolicyRegistryInterface
{
    /**
     * @return array{status: string, expiryDate: string}
     */
    public function verify(string $policyNumber, string $policyHolderId): array
    {
        // Demo: all policies are active with 1-year expiry
        return [
            'status' => 'active',
            'expiryDate' => date('Y-m-d', strtotime('+1 year')),
        ];
    }
}
