<?php

declare(strict_types=1);

namespace Be\Pattern\UserRegistration\Input;

use Be\Pattern\UserRegistration\Being\EmailVerified;
use Be\Framework\Attribute\Be;

/**
 * Registration Input - Being Chain Demo
 *
 * Sequential metamorphosis: Input -> EmailVerified -> PasswordHashed -> ProfileEnriched -> UserRegistered
 *
 * @link https://schema.org/RegisterAction
 */
#[Be([EmailVerified::class])]
final readonly class RegistrationInput
{
    public function __construct(
        public string $email,
        public string $password,
        public string $displayName,
    ) {
    }
}
