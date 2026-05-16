<?php

declare(strict_types=1);

namespace Be\Pattern\BlogPublishing\Semantic;

use Be\Framework\Attribute\Validate;
use Be\Pattern\BlogPublishing\Exception\InvalidSlugException;

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
            throw new InvalidSlugException();
        }
    }
}
