<?php

declare(strict_types=1);

namespace Be\Demo\UserRegistration\Reason;

/**
 * Gravatar Resolver - Reason (stateless service)
 *
 * Resolves Gravatar avatar URL from email address.
 *
 * @link https://docs.gravatar.com/api/avatars/
 */
final class GravatarResolver
{
    public function resolve(string $email): string
    {
        $hash = md5(strtolower(trim($email)));

        return sprintf('https://www.gravatar.com/avatar/%s', $hash);
    }
}
