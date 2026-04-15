<?php

declare(strict_types=1);

namespace Be\Pattern\UserRegistration\Semantic;

use Be\Pattern\UserRegistration\Exception\InvalidEmailException;
use Be\Framework\Attribute\Validate;

/**
 * Email - Semantic validation
 *
 * Validates RFC-compliant email format.
 *
 * @link https://schema.org/email
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
