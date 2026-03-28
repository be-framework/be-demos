<?php

declare(strict_types=1);

namespace Be\Demo\BlogPublishing\Semantic;

use Be\Framework\Attribute\Validate;
use InvalidArgumentException;

/**
 * Slug - Semantic validation
 *
 * Validates URL-friendly slug format.
 */
final class Slug
{
    #[Validate]
    public function validate(string $slug): void
    {
        if (!preg_match('/^[a-z0-9-]+$/', $slug)) {
            throw new InvalidArgumentException('Invalid slug format');
        }
    }
}
