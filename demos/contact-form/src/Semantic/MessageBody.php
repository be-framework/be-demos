<?php

declare(strict_types=1);

namespace Be\Demo\ContactForm\Semantic;

use Be\Demo\ContactForm\Exception\InvalidMessageException;
use Be\Framework\Attribute\Validate;

/**
 * MessageBody - Semantic validation
 *
 * Validates that the message body meets minimum and maximum length requirements.
 */
final class MessageBody
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
