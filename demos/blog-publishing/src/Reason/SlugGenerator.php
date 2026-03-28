<?php

declare(strict_types=1);

namespace Be\Demo\BlogPublishing\Reason;

/**
 * Slug Generator - Reason (stateless service)
 *
 * Converts an article title into a URL-friendly slug.
 */
final class SlugGenerator
{
    public function generate(string $title): string
    {
        // Lowercase
        $slug = mb_strtolower($title);

        // Replace spaces with hyphens
        $slug = preg_replace('/\s+/', '-', $slug) ?? $slug;

        // Remove special characters (keep alphanumeric and hyphens)
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug) ?? $slug;

        // Collapse multiple hyphens
        $slug = preg_replace('/-+/', '-', $slug) ?? $slug;

        // Trim hyphens from edges
        $slug = trim($slug, '-');

        // Fallback for empty slugs (e.g. non-ASCII-only titles)
        return $slug !== '' ? $slug : 'untitled';
    }
}
