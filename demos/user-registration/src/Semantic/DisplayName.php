<?php

declare(strict_types=1);

namespace Be\Pattern\UserRegistration\Semantic;

use Be\Pattern\UserRegistration\Exception\InvalidDisplayNameException;
use Be\Framework\Attribute\Validate;

/**
 * Display Name - Semantic validation
 *
 * Validates display name: 2-50 characters, no control characters.
 *
 * @link https://schema.org/name
 */
final class DisplayName
{
    #[Validate]
    public function validate(string $displayName): void
    {
        $length = mb_strlen($displayName);

        if ($length < 2 || $length > 50) {
            throw new InvalidDisplayNameException();
        }

        if (preg_match('/[\x00-\x1F\x7F]/', $displayName)) {
            throw new InvalidDisplayNameException();
        }
    }
}
