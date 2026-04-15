<?php

declare(strict_types=1);

namespace Be\Pattern\BlogPublishing\Reason;

/**
 * Excerpt Extractor - Reason (stateless service)
 *
 * Extracts a plain-text excerpt from HTML content.
 */
final class ExcerptExtractor
{
    public function extract(string $html): string
    {
        // Strip HTML tags
        $text = strip_tags($html);

        // Normalize whitespace
        $text = preg_replace('/\s+/', ' ', $text) ?? $text;
        $text = trim($text);

        // Return first 200 characters
        if (mb_strlen($text) <= 200) {
            return $text;
        }

        return mb_substr($text, 0, 200) . '...';
    }
}
