<?php

declare(strict_types=1);

namespace Be\Pattern\BlogPublishing\Semantic;

use Be\Pattern\BlogPublishing\Exception\InvalidBodyException;
use Be\Framework\Attribute\Validate;

/**
 * Markdown Body
 *
 * @link https://schema.org/articleBody
 */
final class MarkdownBody
{
    #[Validate]
    public function validate(string $markdownBody): void
    {
        $length = mb_strlen(trim($markdownBody));

        if ($length < 50) {
            throw new InvalidBodyException();
        }

        if ($length > 50000) {
            throw new InvalidBodyException();
        }
    }
}
