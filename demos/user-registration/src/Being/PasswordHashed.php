<?php

declare(strict_types=1);

namespace Be\Demo\UserRegistration\Being;

use Be\Demo\UserRegistration\Reason\PasswordHasher;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Password Hashed - Being (sequential chain step 2)
 *
 * Hashes the plain-text password using bcrypt.
 * Produces a hashed password for the Final state.
 */
final readonly class PasswordHashed
{
    public string $hashedPassword;

    public function __construct(
        #[Input] public string $password,
        #[Inject] PasswordHasher $hasher,
    ) {
        $this->hashedPassword = $hasher->hash($password);
    }
}
