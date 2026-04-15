<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Reason;

/**
 * Identity Verifier - Reason for identity verification
 *
 * Verifies applicant identity and returns a verification ID.
 */
final class IdentityVerifier implements IdentityVerifierInterface
{
    public function verify(string $applicantId): string
    {
        // Demo: generate verification ID based on applicant
        return sprintf(
            'VER-%s-%s',
            date('Ymd'),
            substr(md5($applicantId . time()), 0, 8)
        );
    }
}
