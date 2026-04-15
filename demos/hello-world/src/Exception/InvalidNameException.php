<?php

declare(strict_types=1);

namespace Be\Pattern\Hello\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

/**
 * Semantic validation exception for invalid name
 */
#[Message([
    'en' => 'Invalid name.',
    'ja' => '無効な名前です。'
])]
final class InvalidNameException extends DomainException
{
}
