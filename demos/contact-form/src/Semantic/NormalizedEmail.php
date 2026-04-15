<?php

declare(strict_types=1);

namespace Be\Pattern\ContactForm\Semantic;

use Be\Pattern\ContactForm\Exception\InvalidEmailException;
use Be\Framework\Attribute\Validate;

/**
 * NormalizedEmail - Semantic validation
 *
 * Validates that the normalized email is well-formed.
 * Since it's already normalized, this is a sanity check.
 */
final class NormalizedEmail
{
    #[Validate]
    public function validate(string $normalizedEmail): void
    {
        if (filter_var($normalizedEmail, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidEmailException();
        }
    }
}
