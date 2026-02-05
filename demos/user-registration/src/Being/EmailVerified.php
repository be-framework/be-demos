<?php

declare(strict_types=1);

namespace Be\Demo\UserRegistration\Being;

use Be\Demo\UserRegistration\Exception\DuplicateEmailException;
use Be\Demo\UserRegistration\Reason\EmailVerifierInterface;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Email Verified - Being (sequential chain step 1)
 *
 * Verifies that the email address is not already registered.
 * Produces a verified email for downstream Beings.
 */
final readonly class EmailVerified
{
    public string $verifiedEmail;

    public function __construct(
        #[Input] public string $email,
        #[Inject] EmailVerifierInterface $verifier,
    ) {
        if (!$verifier->isAvailable($email)) {
            throw new DuplicateEmailException();
        }

        $this->verifiedEmail = $email;
    }
}
