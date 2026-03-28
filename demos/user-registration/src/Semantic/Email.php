<?php

declare(strict_types=1);

namespace Be\Demo\UserRegistration\Semantic;

use Be\Demo\UserRegistration\Exception\InvalidEmailException;
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
