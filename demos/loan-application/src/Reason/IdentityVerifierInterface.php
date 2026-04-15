<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Reason;

/**
 * Identity Verifier Interface
 *
 * Enables testability through dependency injection.
 */
interface IdentityVerifierInterface
{
    public function verify(string $applicantId): string;
}
