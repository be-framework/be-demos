<?php

declare(strict_types=1);

namespace Be\Pattern\UserRegistration\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Password does not meet requirements.',
    'ja' => 'パスワードが要件を満たしていません。'
])]
final class WeakPasswordException extends DomainException
{
}
