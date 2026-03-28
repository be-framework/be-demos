<?php

declare(strict_types=1);

namespace Be\Demo\UserRegistration\Being;

use Be\Demo\UserRegistration\Reason\GravatarResolver;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Profile Enriched - Being (sequential chain step 3)
 *
 * Resolves the avatar URL from the email using Gravatar.
 * Produces an avatar URL for the Final state.
 */
final readonly class ProfileEnriched
{
    public string $avatarUrl;

    public function __construct(
        #[Input] public string $email,
        #[Input] public string $displayName,
        #[Inject] GravatarResolver $resolver,
    ) {
        $this->avatarUrl = $resolver->resolve($email);
    }
}
