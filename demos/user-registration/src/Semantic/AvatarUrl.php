<?php

declare(strict_types=1);

namespace Be\Demo\UserRegistration\Semantic;

use Be\Framework\Attribute\Validate;
use InvalidArgumentException;

/**
 * AvatarUrl - Semantic validation
 *
 * Validates that the avatar URL is a valid URL.
 *
 * @link https://schema.org/image
 */
final class AvatarUrl
{
    #[Validate]
    public function validate(string $avatarUrl): void
    {
        if (filter_var($avatarUrl, FILTER_VALIDATE_URL) === false) {
            throw new InvalidArgumentException('Invalid avatar URL');
        }
    }
}
