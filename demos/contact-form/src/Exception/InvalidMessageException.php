<?php

declare(strict_types=1);

namespace Be\Pattern\ContactForm\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

/**
 * Semantic validation exception for invalid message body
 */
#[Message([
    'en' => 'Invalid message body.',
    'ja' => '無効なメッセージです。'
])]
final class InvalidMessageException extends DomainException
{
}
