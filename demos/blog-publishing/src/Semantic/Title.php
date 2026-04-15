<?php

declare(strict_types=1);

namespace Be\Pattern\BlogPublishing\Semantic;

use Be\Pattern\BlogPublishing\Exception\InvalidTitleException;
use Be\Framework\Attribute\Validate;

/**
 * Title - Semantic validation
 *
 * Validates article title length.
 *
 * @link https://schema.org/headline
 */
final class Title
{
    #[Validate]
    public function validate(string $title): void
    {
        $length = mb_strlen(trim($title));

        if ($length < 1) {
            throw new InvalidTitleException();
        }

        if ($length > 200) {
            throw new InvalidTitleException();
        }
    }
}
