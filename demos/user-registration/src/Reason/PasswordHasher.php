<?php

declare(strict_types=1);

namespace Be\Pattern\UserRegistration\Reason;

/**
 * Password Hasher - Reason (stateless service)
 *
 * Hashes passwords using bcrypt algorithm.
 */
final class PasswordHasher
{
    public function hash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}
