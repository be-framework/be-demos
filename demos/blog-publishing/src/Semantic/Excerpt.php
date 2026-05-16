<?php

declare(strict_types=1);

namespace Be\Pattern\BlogPublishing\Semantic;

use Be\Framework\Attribute\Validate;
use Be\Pattern\BlogPublishing\Exception\InvalidExcerptException;

/**
 * Excerpt - Semantic validation
 *
 * Validates that the excerpt is within length limits.
 */
final class Excerpt
{
    #[Validate]
    public function validate(string $excerpt): void
    {
        if (mb_strlen($excerpt) > 250) {
            throw new InvalidExcerptException();
        }
    }
}
