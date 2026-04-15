<?php

declare(strict_types=1);

namespace Be\Pattern\BlogPublishing\Semantic;

use Be\Pattern\BlogPublishing\Exception\InvalidTagException;
use Be\Framework\Attribute\Validate;

/**
 * Tag
 *
 * @link https://schema.org/keywords
 */
final class Tag
{
    #[Validate]
    public function validate(string $tag): void
    {
        if (!preg_match('/^[a-z0-9\-]+$/', $tag)) {
            throw new InvalidTagException();
        }

        if (mb_strlen($tag) > 30) {
            throw new InvalidTagException();
        }
    }
}
