<?php

declare(strict_types=1);

namespace Be\Demo\UserRegistration\Reason;

/**
 * Password Hasher - Reason (stateless service)
 *
 * Hashes passwords using bcrypt algorithm.
 */
final class PasswordHasher
{
    public function hash(string $password): string
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);

        if ($hash === false) {
            throw new \RuntimeException('Password hashing failed');
        }

        return $hash;
    }
}
