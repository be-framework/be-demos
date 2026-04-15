<?php

declare(strict_types=1);

namespace Be\Pattern\UserRegistration\Being;

use Be\Pattern\UserRegistration\Final\UserRegistered;
use Be\Pattern\UserRegistration\Reason\GravatarResolver;
use Be\Framework\Attribute\Be;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Profile Enriched - Being (sequential chain step 3)
 *
 * Resolves the avatar URL from the email using Gravatar.
 * Carries all properties needed by the Final state.
 */
#[Be([UserRegistered::class])]
final readonly class ProfileEnriched
{
    public string $avatarUrl;

    public function __construct(
        #[Input] public string $email,
        #[Input] public string $displayName,
        #[Input] public string $hashedPassword,
        #[Inject] GravatarResolver $resolver,
    ) {
        $this->avatarUrl = $resolver->resolve($email);
    }
}
