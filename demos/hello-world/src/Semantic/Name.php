<?php

declare(strict_types=1);

namespace Be\Pattern\Hello\Semantic;

use Be\Pattern\Hello\Exception\InvalidNameException;
use Be\Framework\Attribute\Validate;

use function trim;

/**
 * Name - Semantic validation
 *
 * Validates that the name is non-empty.
 *
 * @link https://schema.org/name
 */
final class Name
{
    #[Validate]
    public function validate(string $name): void
    {
        if (empty(trim($name))) {
            throw new InvalidNameException();
        }
    }
}
