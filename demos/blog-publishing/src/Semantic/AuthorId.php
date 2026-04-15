<?php

declare(strict_types=1);

namespace Be\Pattern\BlogPublishing\Semantic;

use Be\Pattern\BlogPublishing\Exception\InvalidAuthorException;
use Be\Framework\Attribute\Validate;

/**
 * Author ID
 *
 * @link https://schema.org/identifier
 */
final class AuthorId
{
    #[Validate]
    public function validate(string $authorId): void
    {
        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $authorId)) {
            throw new InvalidAuthorException();
        }
    }
}
