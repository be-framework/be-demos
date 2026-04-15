<?php

declare(strict_types=1);

namespace Be\Pattern\UserRegistration\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid email address.',
    'ja' => '無効なメールアドレスです。'
])]
final class InvalidEmailException extends DomainException
{
}
