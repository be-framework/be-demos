<?php

declare(strict_types=1);

namespace Be\Demo\UserRegistration\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid display name.',
    'ja' => '無効な表示名です。'
])]
final class InvalidDisplayNameException extends DomainException
{
}
