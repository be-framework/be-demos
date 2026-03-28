<?php

declare(strict_types=1);

namespace Be\Demo\UserRegistration\Being;

use Be\Demo\UserRegistration\Reason\PasswordHasher;
use Be\Framework\Attribute\Be;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Password Hashed - Being (sequential chain step 2)
 *
 * Hashes the plain-text password using bcrypt.
 * Carries email and displayName forward for ProfileEnriched.
 */
#[Be([ProfileEnriched::class])]
final readonly class PasswordHashed
{
    public string $hashedPassword;

    public function __construct(
        #[Input] public string $email,
        #[Input] public string $password,
        #[Input] public string $displayName,
        #[Inject] PasswordHasher $hasher,
    ) {
        $this->hashedPassword = $hasher->hash($password);
    }
}
