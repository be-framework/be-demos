<?php

declare(strict_types=1);

namespace Be\Pattern\UserRegistration\Semantic;

use Be\Framework\Attribute\Validate;
use Be\Pattern\UserRegistration\Exception\InvalidHashedPasswordException;

/**
 * HashedPassword - Semantic validation
 *
 * Validates that the hashed password is a valid bcrypt hash.
 */
final class HashedPassword
{
    #[Validate]
    public function validate(string $hashedPassword): void
    {
        // Bcrypt hashes start with $2y$ or $2a$ and are 60 characters
        if (!preg_match('/^\$2[aby]\$\d{2}\$.{53}$/', $hashedPassword)) {
            throw new InvalidHashedPasswordException();
        }
    }
}
