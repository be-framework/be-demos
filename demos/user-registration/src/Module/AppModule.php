<?php

declare(strict_types=1);

namespace Be\Pattern\UserRegistration\Module;

use Be\Framework\Module\BeModule;
use Be\Pattern\UserRegistration\Reason\EmailVerifier;
use Be\Pattern\UserRegistration\Reason\EmailVerifierInterface;
use Be\Pattern\UserRegistration\Reason\GravatarResolver;
use Be\Pattern\UserRegistration\Reason\PasswordHasher;
use Be\Pattern\UserRegistration\Reason\UserIdGenerator;
use Be\Pattern\UserRegistration\Reason\WelcomeTokenGenerator;
use Ray\Di\AbstractModule;

final class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->install(new BeModule('Be\Pattern\UserRegistration\Semantic'));

        $this->bind(EmailVerifierInterface::class)->to(EmailVerifier::class);

        $this->bind(PasswordHasher::class);
        $this->bind(GravatarResolver::class);
        $this->bind(UserIdGenerator::class);
        $this->bind(WelcomeTokenGenerator::class);
    }
}
