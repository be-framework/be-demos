<?php

declare(strict_types=1);

namespace Be\Demo\ContactForm\Input;

use Be\Demo\ContactForm\Final\ContactReceived;
use Be\Framework\Attribute\Be;

/**
 * Input for Contact Form transformation
 *
 * Captures contact form submission data.
 * Declares its potential to become ContactReceived.
 */
#[Be([ContactReceived::class])]
final readonly class ContactInput
{
    public function __construct(
        public string $name,
        public string $email,
        public string $subject,
        public string $message,
    ) {
    }
}
