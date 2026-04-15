<?php

declare(strict_types=1);

namespace Be\Pattern\ContactForm\Semantic;

use Be\Pattern\ContactForm\Exception\InvalidMessageException;
use Be\Framework\Attribute\Validate;

/**
 * Message - Semantic validation
 *
 * Validates that the message meets minimum and maximum length requirements.
 */
final class Message
{
    #[Validate]
    public function validate(string $message): void
    {
        if (mb_strlen($message) < 10) {
            throw new InvalidMessageException();
        }

        if (mb_strlen($message) > 5000) {
            throw new InvalidMessageException();
        }
    }
}
