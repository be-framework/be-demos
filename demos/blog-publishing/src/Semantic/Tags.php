<?php

declare(strict_types=1);

namespace Be\Pattern\BlogPublishing\Semantic;

use Be\Pattern\BlogPublishing\Exception\InvalidTagException;
use Be\Framework\Attribute\Validate;

/**
 * Tags - Semantic validation for array of tags
 *
 * Validates that tags array is not empty and each tag is valid.
 */
final class Tags
{
    #[Validate]
    public function validate(array $tags): void
    {
        if (empty($tags)) {
            throw new InvalidTagException('At least one tag is required');
        }

        foreach ($tags as $tag) {
            if (!preg_match('/^[a-z0-9-]+$/', $tag)) {
                throw new InvalidTagException();
            }

            if (mb_strlen($tag) > 30) {
                throw new InvalidTagException();
            }
        }
    }
}
