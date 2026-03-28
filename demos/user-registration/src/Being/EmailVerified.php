<?php

declare(strict_types=1);

namespace Be\Demo\UserRegistration\Being;

use Be\Demo\UserRegistration\Exception\DuplicateEmailException;
use Be\Demo\UserRegistration\Reason\EmailVerifierInterface;
use Be\Framework\Attribute\Be;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Email Verified - Being (sequential chain step 1)
 *
 * Verifies that the email address is not already registered.
 * Carries all properties forward for downstream Beings.
 */
#[Be([PasswordHashed::class])]
final readonly class EmailVerified
{
    public function __construct(
        #[Input] public string $email,
        #[Input] public string $password,
        #[Input] public string $displayName,
        #[Inject] EmailVerifierInterface $verifier,
    ) {
        if (!$verifier->isAvailable($email)) {
            throw new DuplicateEmailException();
        }
    }
}
