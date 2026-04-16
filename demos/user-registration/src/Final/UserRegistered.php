<?php

declare(strict_types=1);

namespace Be\Pattern\UserRegistration\Final;

use Be\Framework\SemanticLog\Been;
use Be\Pattern\UserRegistration\Context\UserCreatedContext;
use Be\Pattern\UserRegistration\Reason\UserIdGenerator;
use Be\Pattern\UserRegistration\Reason\WelcomeTokenGenerator;
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
    public Been $been;

    public function __construct(
        #[Input] public string $email,
        #[Input] public string $displayName,
        #[Input] public string $hashedPassword,
        #[Input] public string $avatarUrl,
        #[Inject] UserIdGenerator $idGen,
        #[Inject] WelcomeTokenGenerator $tokenGen,
        #[Inject] Been $been,
    ) {
        $this->userId = $idGen->generate();
        $this->welcomeToken = $tokenGen->generate($email);
        $this->been = $been->with(new UserCreatedContext(
            userId: $this->userId,
            email: $email,
        ));

        // Proof: the user was created with the input email
        $event = $this->been->events[0];
        assert($event instanceof UserCreatedContext);
        assert($event->email === $this->email, 'User must be created with the input email');
    }
}
