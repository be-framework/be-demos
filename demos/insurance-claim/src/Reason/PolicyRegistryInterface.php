<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Reason;

/**
 * Policy Registry Interface
 *
 * Enables testability through dependency injection.
 *
 * @return array{status: string, expiryDate: string}
 */
interface PolicyRegistryInterface
{
    /**
     * @return array{status: string, expiryDate: string}
     */
    public function verify(string $policyNumber, string $policyHolderId): array;
}
