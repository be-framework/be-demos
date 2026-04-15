<?php

declare(strict_types=1);

namespace Be\Pattern\BlogPublishing\Reason;

/**
 * Author Resolver Interface
 *
 * Enables testability through dependency injection.
 */
interface AuthorResolverInterface
{
    public function resolve(string $authorId): string;
}
