<?php

declare(strict_types=1);

namespace Be\Demo\UserRegistration\Reason;

/**
 * Welcome Token Generator - Reason (stateless service)
 *
 * Generates a welcome/verification token for new users.
 */
final class WelcomeTokenGenerator
{
    public function generate(string $email): string
    {
        return hash('sha256', $email . bin2hex(random_bytes(16)));
    }
}
