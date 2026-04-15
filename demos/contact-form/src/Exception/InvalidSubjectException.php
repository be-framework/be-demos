<?php

declare(strict_types=1);

namespace Be\Pattern\ContactForm\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

/**
 * Semantic validation exception for invalid subject line
 */
#[Message([
    'en' => 'Invalid subject line.',
    'ja' => '無効な件名です。'
])]
final class InvalidSubjectException extends DomainException
{
}
