<?php

declare(strict_types=1);

namespace Be\Pattern\UserRegistration\Context;

use Koriym\SemanticLogger\AbstractContext;

final class UserCreatedContext extends AbstractContext
{
    public const string TYPE = 'user_created';
    public const string SCHEMA_URL = 'https://be-framework.github.io/schemas/user-registration/user-created.json';

    public function __construct(
        public readonly string $userId,
        public readonly string $email,
    ) {
    }
}
