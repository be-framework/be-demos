<?php

declare(strict_types=1);

namespace Be\App\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid card number.',
    'ja' => '無効なカード番号です。'
])]
final class InvalidCardNumberException extends DomainException
{
}
