<?php

declare(strict_types=1);

namespace Be\Demo\BlogPublishing\Semantic;

use Be\Demo\BlogPublishing\Exception\InvalidTitleException;
use Be\Framework\Attribute\Validate;

/**
 * Article Title
 *
 * @link https://schema.org/headline
 */
final class ArticleTitle
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
