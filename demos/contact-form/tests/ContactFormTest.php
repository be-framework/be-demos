<?php

declare(strict_types=1);

namespace Be\Demo\ContactForm\Tests;

use Be\Demo\ContactForm\Exception\InvalidEmailException;
use Be\Demo\ContactForm\Exception\InvalidMessageException;
use Be\Demo\ContactForm\Exception\InvalidSubjectException;
use Be\Demo\ContactForm\Final\ContactReceived;
use Be\Demo\ContactForm\Input\ContactInput;
use Be\Demo\ContactForm\Module\AppModule;
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

    public function testInvalidEmailThrowsException(): void
    {
        $this->expectException(InvalidEmailException::class);

        $input = new ContactInput(
            name: 'Test User',
            email: 'not-an-email',
            subject: 'Test subject line',
            message: 'This message is long enough to pass validation.',
        );

        ($this->becoming)($input);
    }

    public function testEmptySubjectThrowsException(): void
    {
        $this->expectException(InvalidSubjectException::class);

        $input = new ContactInput(
            name: 'Test User',
            email: 'test@example.com',
            subject: '',
            message: 'This message is long enough to pass validation.',
        );

        ($this->becoming)($input);
    }

    public function testMessageTooShortThrowsException(): void
    {
        $this->expectException(InvalidMessageException::class);

        $input = new ContactInput(
            name: 'Test User',
            email: 'test@example.com',
            subject: 'Valid subject',
            message: 'Short',
        );

        ($this->becoming)($input);
    }
}
