<?php

declare(strict_types=1);

namespace Be\Demo\UserRegistration\Final;

use Be\Demo\UserRegistration\Reason\UserIdGenerator;
use Be\Demo\UserRegistration\Reason\WelcomeTokenGenerator;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * User Registered - Final (convergence point)
 *
 * The actualization of RegistrationInput's potential.
 * All Being chain results converge here into a registered user.
 *
 * @link https://schema.org/RegisterAction
 */
final readonly class UserRegistered
{
    public string $userId;
    public string $welcomeToken;

    public function __construct(
        #[Input] public string $email,
        #[Input] public string $displayName,
        #[Input] public string $hashedPassword,
        #[Input] public string $avatarUrl,
        #[Inject] UserIdGenerator $idGen,
        #[Inject] WelcomeTokenGenerator $tokenGen,
    ) {
        $this->userId = $idGen->generate();
        $this->welcomeToken = $tokenGen->generate($email);
    }
}
