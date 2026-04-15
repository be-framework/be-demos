<?php

declare(strict_types=1);

namespace Be\Pattern\ContactForm\Semantic;

use Be\Pattern\ContactForm\Exception\InvalidEmailException;
use Be\Framework\Attribute\Validate;

/**
 * Email - Semantic validation
 *
 * Validates that the email address is well-formed.
 */
final class Email
{
    #[Validate]
    public function validate(string $email): void
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidEmailException();
        }
    }
}
