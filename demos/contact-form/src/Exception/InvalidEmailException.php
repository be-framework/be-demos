<?php

declare(strict_types=1);

namespace Be\Demo\ContactForm\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

/**
 * Semantic validation exception for invalid email address
 */
#[Message([
    'en' => 'Invalid email address.',
    'ja' => '無効なメールアドレスです。'
])]
final class InvalidEmailException extends DomainException
{
}
