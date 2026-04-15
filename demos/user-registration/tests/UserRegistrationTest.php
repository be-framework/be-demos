<?php

declare(strict_types=1);

namespace Be\Pattern\UserRegistration\Tests;

use Be\Pattern\UserRegistration\Exception\InvalidDisplayNameException;
use Be\Pattern\UserRegistration\Exception\InvalidEmailException;
use Be\Pattern\UserRegistration\Exception\WeakPasswordException;
use Be\Pattern\UserRegistration\Final\UserRegistered;
use Be\Pattern\UserRegistration\Input\RegistrationInput;
use Be\Pattern\UserRegistration\Module\AppModule;
use Be\Pattern\UserRegistration\Semantic\DisplayName;
use Be\Pattern\UserRegistration\Semantic\Email;
use Be\Pattern\UserRegistration\Semantic\Password;
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
    // Semantic Validation Tests
    // ──────────────────────────────────────────────

    public function testValidEmail(): void
    {
        $semantic = new Email();
        $semantic->validate('test@example.com');
        $this->addToAssertionCount(1);
    }

    public function testInvalidEmailThrowsException(): void
    {
        $this->expectException(InvalidEmailException::class);
        $semantic = new Email();
        $semantic->validate('not-an-email');
    }

    public function testValidPassword(): void
    {
        $semantic = new Password();
        $semantic->validate('Str0ngP@ss');
        $this->addToAssertionCount(1);
    }

    public function testWeakPasswordTooShortThrowsException(): void
    {
        $this->expectException(WeakPasswordException::class);
        $semantic = new Password();
        $semantic->validate('Abc1');
    }

    public function testWeakPasswordMissingUppercaseThrowsException(): void
    {
        $this->expectException(WeakPasswordException::class);
        $semantic = new Password();
        $semantic->validate('lowercase123');
    }

    public function testWeakPasswordMissingLowercaseThrowsException(): void
    {
        $this->expectException(WeakPasswordException::class);
        $semantic = new Password();
        $semantic->validate('UPPERCASE123');
    }

    public function testWeakPasswordMissingDigitThrowsException(): void
    {
        $this->expectException(WeakPasswordException::class);
        $semantic = new Password();
        $semantic->validate('NoDigitsHere');
    }

    public function testValidDisplayName(): void
    {
        $semantic = new DisplayName();
        $semantic->validate('Alice');
        $this->addToAssertionCount(1);
    }

    public function testInvalidDisplayNameTooShortThrowsException(): void
    {
        $this->expectException(InvalidDisplayNameException::class);
        $semantic = new DisplayName();
        $semantic->validate('A');
    }

    public function testInvalidDisplayNameTooLongThrowsException(): void
    {
        $this->expectException(InvalidDisplayNameException::class);
        $semantic = new DisplayName();
        $semantic->validate(str_repeat('a', 51));
    }

    public function testInvalidDisplayNameWithControlCharsThrowsException(): void
    {
        $this->expectException(InvalidDisplayNameException::class);
        $semantic = new DisplayName();
        $semantic->validate("Test\x00User");
    }
}
