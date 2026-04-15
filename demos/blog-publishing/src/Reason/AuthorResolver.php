<?php

declare(strict_types=1);

namespace Be\Pattern\BlogPublishing\Reason;

/**
 * Author Resolver - Reason (stateless service)
 *
 * Resolves an author ID to a display name.
 * Demo implementation returns a mock name.
 */
final class AuthorResolver implements AuthorResolverInterface
{
    public function resolve(string $authorId): string
    {
        // Demo: return a mock author name based on the author ID
        return sprintf('Author-%s', substr($authorId, 0, 8));
    }
}
