<?php

declare(strict_types=1);

namespace Be\Pattern\BlogPublishing\Reason;

/**
 * Publish Timestamper - Reason (stateless service)
 *
 * Provides the current timestamp in ISO 8601 format for publication.
 */
final class PublishTimestamper
{
    public function now(): string
    {
        return date('c');
    }
}
