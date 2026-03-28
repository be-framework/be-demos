<?php

declare(strict_types=1);

namespace Be\Demo\BlogPublishing\Semantic;

use Be\Demo\BlogPublishing\Exception\InvalidTitleException;
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
        $length = mb_strlen($title);

        if ($length < 1) {
            throw new InvalidTitleException();
        }

        if ($length > 200) {
            throw new InvalidTitleException();
        }
    }
}
