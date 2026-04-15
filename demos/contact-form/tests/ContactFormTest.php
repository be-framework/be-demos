<?php

declare(strict_types=1);

namespace Be\Pattern\ContactForm\Tests;

use Be\Pattern\ContactForm\Exception\InvalidEmailException;
use Be\Pattern\ContactForm\Exception\InvalidMessageException;
use Be\Pattern\ContactForm\Exception\InvalidSubjectException;
use Be\Pattern\ContactForm\Final\ContactReceived;
use Be\Pattern\ContactForm\Input\ContactInput;
use Be\Pattern\ContactForm\Module\AppModule;
use Be\Pattern\ContactForm\Semantic\Email;
use Be\Pattern\ContactForm\Semantic\Message;
use Be\Pattern\ContactForm\Semantic\Subject;
use Be\Framework\Becoming;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

class ContactFormTest extends TestCase
{
    private Becoming $becoming;

    protected function setUp(): void
    {
        $injector = new Injector(new AppModule());
        $this->becoming = $injector->getInstance(Becoming::class);
    }

    public function testContactInputBecomesContactReceived(): void
    {
        $input = new ContactInput(
            name: 'John Doe',
            email: 'John.Doe+newsletter@Example.COM',
            subject: 'Hello from the contact form',
            message: 'This is a test message for the contact form demo.',
        );

        /** @var ContactReceived $final */
        $final = ($this->becoming)($input);

        $this->assertInstanceOf(ContactReceived::class, $final);
        $this->assertSame('John Doe', $final->name);
        $this->assertSame('john.doe@example.com', $final->normalizedEmail);
        $this->assertSame('Hello from the contact form', $final->subject);
        $this->assertSame('This is a test message for the contact form demo.', $final->message);
        $this->assertStringStartsWith('RCP-', $final->receiptId);
        $this->assertNotEmpty($final->receivedAt);
    }

    public function testEmailNormalizationRemovesAlias(): void
    {
        $input = new ContactInput(
            name: 'Jane Smith',
            email: 'jane+spam@example.com',
            subject: 'Testing alias removal',
            message: 'The email alias should be removed during normalization.',
        );

        /** @var ContactReceived $final */
        $final = ($this->becoming)($input);

        $this->assertSame('jane@example.com', $final->normalizedEmail);
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

    public function testValidSubject(): void
    {
        $semantic = new Subject();
        $semantic->validate('Hello from the contact form');
        $this->addToAssertionCount(1);
    }

    public function testEmptySubjectThrowsException(): void
    {
        $this->expectException(InvalidSubjectException::class);
        $semantic = new Subject();
        $semantic->validate('');
    }

    public function testWhitespaceOnlySubjectThrowsException(): void
    {
        $this->expectException(InvalidSubjectException::class);
        $semantic = new Subject();
        $semantic->validate('   ');
    }

    public function testSubjectTooLongThrowsException(): void
    {
        $this->expectException(InvalidSubjectException::class);
        $semantic = new Subject();
        $semantic->validate(str_repeat('a', 201));
    }

    public function testValidMessage(): void
    {
        $semantic = new Message();
        $semantic->validate('This message is long enough to pass validation.');
        $this->addToAssertionCount(1);
    }

    public function testMessageTooShortThrowsException(): void
    {
        $this->expectException(InvalidMessageException::class);
        $semantic = new Message();
        $semantic->validate('Short');
    }

    public function testMessageTooLongThrowsException(): void
    {
        $this->expectException(InvalidMessageException::class);
        $semantic = new Message();
        $semantic->validate(str_repeat('a', 5001));
    }
}
