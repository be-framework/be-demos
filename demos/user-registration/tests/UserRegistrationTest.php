<?php

declare(strict_types=1);

namespace Be\Demo\UserRegistration\Tests;

use Be\Demo\UserRegistration\Final\UserRegistered;
use Be\Demo\UserRegistration\Input\RegistrationInput;
use Be\Demo\UserRegistration\Module\AppModule;
use Be\Framework\Becoming;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

class UserRegistrationTest extends TestCase
{
    private Becoming $becoming;

    protected function setUp(): void
    {
        $injector = new Injector(new AppModule());
        $this->becoming = $injector->getInstance(Becoming::class);
    }

    public function testRegistrationInputBecomesUserRegistered(): void
    {
        $input = new RegistrationInput(
            email: 'alice@example.com',
            password: 'Str0ngP@ss',
            displayName: 'Alice'
        );

        /** @var UserRegistered $final */
        $final = ($this->becoming)($input);

        $this->assertInstanceOf(UserRegistered::class, $final);
        $this->assertSame('alice@example.com', $final->email);
        $this->assertSame('Alice', $final->displayName);

        // Verify password was hashed (bcrypt hashes start with $2y$)
        $this->assertStringStartsWith('$2y$', $final->hashedPassword);

        // Verify avatar URL is a valid Gravatar URL
        $expectedHash = md5(strtolower(trim('alice@example.com')));
        $this->assertSame(
            sprintf('https://www.gravatar.com/avatar/%s', $expectedHash),
            $final->avatarUrl
        );

        // Verify user ID and welcome token are generated
        $this->assertStringStartsWith('USR-', $final->userId);
        $this->assertNotEmpty($final->welcomeToken);
    }

    public function testRegistrationWithDifferentUser(): void
    {
        $input = new RegistrationInput(
            email: 'bob@example.com',
            password: 'B0bSecure!',
            displayName: 'Bob Smith'
        );

        /** @var UserRegistered $final */
        $final = ($this->becoming)($input);

        $this->assertInstanceOf(UserRegistered::class, $final);
        $this->assertSame('bob@example.com', $final->email);
        $this->assertSame('Bob Smith', $final->displayName);
        $this->assertStringStartsWith('$2y$', $final->hashedPassword);
        $this->assertStringStartsWith('USR-', $final->userId);
        $this->assertNotEmpty($final->welcomeToken);
    }
}
