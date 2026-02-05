<?php

declare(strict_types=1);

namespace Be\Demo\UserRegistration\Input;

use Be\Demo\UserRegistration\Final\UserRegistered;
use Be\Framework\Attribute\Be;

/**
 * Registration Input - Being Chain Demo
 *
 * Sequential metamorphosis: Input -> EmailVerified -> PasswordHashed -> ProfileEnriched -> UserRegistered
 *
 * @link https://schema.org/RegisterAction
 */
#[Be([UserRegistered::class])]
final readonly class RegistrationInput
{
    public function __construct(
        public string $email,
        public string $password,
        public string $displayName,
    ) {
    }
}
