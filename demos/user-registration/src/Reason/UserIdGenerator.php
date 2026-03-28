<?php

declare(strict_types=1);

namespace Be\Demo\UserRegistration\Reason;

/**
 * User ID Generator - Reason (stateless service)
 *
 * Generates a unique user identifier.
 */
final class UserIdGenerator
{
    public function generate(): string
    {
        return sprintf(
            'USR-%s-%s',
            date('Ymd'),
            substr(bin2hex(random_bytes(8)), 0, 16)
        );
    }
}
