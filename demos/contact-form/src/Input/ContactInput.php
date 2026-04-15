<?php

declare(strict_types=1);

namespace Be\Pattern\ContactForm\Input;

use Be\Pattern\ContactForm\Being\EmailNormalized;
use Be\Framework\Attribute\Be;

/**
 * Input for Contact Form transformation
 *
 * Captures contact form submission data.
 * Declares its potential to become EmailNormalized.
 */
#[Be([EmailNormalized::class])]
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
