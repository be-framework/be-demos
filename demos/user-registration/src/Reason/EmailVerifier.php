<?php

declare(strict_types=1);

namespace Be\Demo\UserRegistration\Reason;

/**
 * Email Verifier - Reason (stateless service)
 *
 * Checks whether an email address is available for registration.
 * Demo implementation always returns true.
 */
final class EmailVerifier implements EmailVerifierInterface
{
    public function isAvailable(string $email): bool
    {
        // Demo: always available
        // In production: return !$this->repository->existsByEmail($email);
        return true;
    }
}
