<?php

declare(strict_types=1);

namespace Be\Demo\BlogPublishing\Semantic;

use Be\Framework\Attribute\Validate;
use InvalidArgumentException;

/**
 * AuthorName - Semantic validation
 *
 * Validates that the author name is non-empty.
 *
 * @link https://schema.org/author
 */
final class AuthorName
{
    #[Validate]
    public function validate(string $authorName): void
    {
        if (empty(trim($authorName))) {
            throw new InvalidArgumentException('Author name cannot be empty');
        }
    }
}
