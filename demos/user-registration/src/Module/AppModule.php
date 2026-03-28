<?php

declare(strict_types=1);

namespace Be\Demo\UserRegistration\Module;

use Be\Demo\UserRegistration\Reason\EmailVerifier;
use Be\Demo\UserRegistration\Reason\EmailVerifierInterface;
use Be\Demo\UserRegistration\Reason\GravatarResolver;
use Be\Demo\UserRegistration\Reason\PasswordHasher;
use Be\Demo\UserRegistration\Reason\UserIdGenerator;
use Be\Demo\UserRegistration\Reason\WelcomeTokenGenerator;
use Ray\Di\AbstractModule;

final class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        // Reason bindings (via interface for testability)
        $this->bind(EmailVerifierInterface::class)->to(EmailVerifier::class);

        // Reason bindings (concrete classes)
        $this->bind(PasswordHasher::class);
        $this->bind(GravatarResolver::class);
        $this->bind(UserIdGenerator::class);
        $this->bind(WelcomeTokenGenerator::class);
    }
}
