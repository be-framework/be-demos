<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Being;

use Be\Pattern\LoanApplication\Reason\IdentityVerifierInterface;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Identity Verified - Being (first stage entry)
 *
 * Verifies the applicant's identity before proceeding to parallel evaluation.
 */
final readonly class IdentityVerified
{
    public string $verificationId;

    public function __construct(
        #[Input] public string $applicantId,
        #[Inject] IdentityVerifierInterface $verifier
    ) {
        $this->verificationId = $verifier->verify($applicantId);
    }
}
