<?php

declare(strict_types=1);

namespace Be\Demo\BlogPublishing\Semantic;

use Be\Framework\Attribute\Validate;
use InvalidArgumentException;

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
            throw new InvalidArgumentException('Excerpt too long');
        }
    }
}
