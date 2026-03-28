<?php

declare(strict_types=1);

namespace Be\Demo\ContactForm\Tests;

use Be\Demo\ContactForm\Exception\InvalidEmailException;
use Be\Demo\ContactForm\Exception\InvalidMessageException;
use Be\Demo\ContactForm\Exception\InvalidSubjectException;
use Be\Demo\ContactForm\Final\ContactReceived;
use Be\Demo\ContactForm\Input\ContactInput;
use Be\Demo\ContactForm\Module\AppModule;
use Be\Demo\ContactForm\Semantic\Email;
use Be\Demo\ContactForm\Semantic\MessageBody;
use Be\Demo\ContactForm\Semantic\SubjectLine;
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

    public function testValidSubjectLine(): void
    {
        $semantic = new SubjectLine();
        $semantic->validate('Hello from the contact form');
        $this->addToAssertionCount(1);
    }

    public function testEmptySubjectThrowsException(): void
    {
        $this->expectException(InvalidSubjectException::class);
        $semantic = new SubjectLine();
        $semantic->validate('');
    }

    public function testWhitespaceOnlySubjectThrowsException(): void
    {
        $this->expectException(InvalidSubjectException::class);
        $semantic = new SubjectLine();
        $semantic->validate('   ');
    }

    public function testSubjectLineTooLongThrowsException(): void
    {
        $this->expectException(InvalidSubjectException::class);
        $semantic = new SubjectLine();
        $semantic->validate(str_repeat('a', 201));
    }

    public function testValidMessageBody(): void
    {
        $semantic = new MessageBody();
        $semantic->validate('This message is long enough to pass validation.');
        $this->addToAssertionCount(1);
    }

    public function testMessageTooShortThrowsException(): void
    {
        $this->expectException(InvalidMessageException::class);
        $semantic = new MessageBody();
        $semantic->validate('Short');
    }

    public function testMessageTooLongThrowsException(): void
    {
        $this->expectException(InvalidMessageException::class);
        $semantic = new MessageBody();
        $semantic->validate(str_repeat('a', 5001));
    }
}
