<?php

declare(strict_types=1);

namespace Be\Demo\UserRegistration\Semantic;

use Be\Demo\UserRegistration\Exception\WeakPasswordException;
use Be\Framework\Attribute\Validate;

/**
 * Password - Semantic validation
 *
 * Validates password strength: minimum 8 characters,
 * requires uppercase, lowercase, and digit.
 */
final class Password
{
    #[Validate]
    public function validate(string $password): void
    {
        if (strlen($password) < 8) {
            throw new WeakPasswordException();
        }

        if (!preg_match('/[A-Z]/', $password)) {
            throw new WeakPasswordException();
        }

        if (!preg_match('/[a-z]/', $password)) {
            throw new WeakPasswordException();
        }

        if (!preg_match('/[0-9]/', $password)) {
            throw new WeakPasswordException();
        }
    }
}
