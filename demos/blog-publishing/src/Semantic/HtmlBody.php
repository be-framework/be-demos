<?php

declare(strict_types=1);

namespace Be\Pattern\BlogPublishing\Semantic;

use Be\Framework\Attribute\Validate;
use Be\Pattern\BlogPublishing\Exception\InvalidHtmlBodyException;

/**
 * HtmlBody - Semantic validation
 *
 * Validates that the rendered HTML body is non-empty.
 */
final class HtmlBody
{
    #[Validate]
    public function validate(string $htmlBody): void
    {
        if (empty(trim(strip_tags($htmlBody)))) {
            throw new InvalidHtmlBodyException();
        }
    }
}
