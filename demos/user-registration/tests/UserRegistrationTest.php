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

    // ──────────────────────────────────────────────
    // Negative Test Cases
    // ──────────────────────────────────────────────

    public function testInvalidEmailThrowsException(): void
    {
        $this->expectException(\Be\Demo\UserRegistration\Exception\InvalidEmailException::class);

        $input = new RegistrationInput(
            email: 'not-an-email',
            password: 'Str0ngP@ss',
            displayName: 'Test User'
        );

        ($this->becoming)($input);
    }

    public function testWeakPasswordTooShortThrowsException(): void
    {
        $this->expectException(\Be\Demo\UserRegistration\Exception\WeakPasswordException::class);

        $input = new RegistrationInput(
            email: 'test@example.com',
            password: 'Abc1',
            displayName: 'Test User'
        );

        ($this->becoming)($input);
    }

    public function testWeakPasswordMissingUppercaseThrowsException(): void
    {
        $this->expectException(\Be\Demo\UserRegistration\Exception\WeakPasswordException::class);

        $input = new RegistrationInput(
            email: 'test@example.com',
            password: 'lowercase123',
            displayName: 'Test User'
        );

        ($this->becoming)($input);
    }

    public function testWeakPasswordMissingLowercaseThrowsException(): void
    {
        $this->expectException(\Be\Demo\UserRegistration\Exception\WeakPasswordException::class);

        $input = new RegistrationInput(
            email: 'test@example.com',
            password: 'UPPERCASE123',
            displayName: 'Test User'
        );

        ($this->becoming)($input);
    }

    public function testWeakPasswordMissingDigitThrowsException(): void
    {
        $this->expectException(\Be\Demo\UserRegistration\Exception\WeakPasswordException::class);

        $input = new RegistrationInput(
            email: 'test@example.com',
            password: 'NoDigitsHere',
            displayName: 'Test User'
        );

        ($this->becoming)($input);
    }

    public function testInvalidDisplayNameTooShortThrowsException(): void
    {
        $this->expectException(\Be\Demo\UserRegistration\Exception\InvalidDisplayNameException::class);

        $input = new RegistrationInput(
            email: 'test@example.com',
            password: 'Str0ngP@ss',
            displayName: 'A'
        );

        ($this->becoming)($input);
    }

    public function testInvalidDisplayNameTooLongThrowsException(): void
    {
        $this->expectException(\Be\Demo\UserRegistration\Exception\InvalidDisplayNameException::class);

        $input = new RegistrationInput(
            email: 'test@example.com',
            password: 'Str0ngP@ss',
            displayName: str_repeat('a', 51)
        );

        ($this->becoming)($input);
    }

    public function testInvalidDisplayNameWithControlCharsThrowsException(): void
    {
        $this->expectException(\Be\Demo\UserRegistration\Exception\InvalidDisplayNameException::class);

        $input = new RegistrationInput(
            email: 'test@example.com',
            password: 'Str0ngP@ss',
            displayName: "Test\x00User"
        );

        ($this->becoming)($input);
    }
}
