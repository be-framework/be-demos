<?php

declare(strict_types=1);

namespace Be\Pattern\UserRegistration\Reason;

/**
 * Email Verifier Interface
 *
 * Enables testability through dependency injection.
 */
interface EmailVerifierInterface
{
    public function isAvailable(string $email): bool;
}
