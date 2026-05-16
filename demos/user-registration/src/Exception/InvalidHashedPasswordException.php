<?php

declare(strict_types=1);

namespace Be\Pattern\UserRegistration\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid password hash format.',
    'ja' => 'パスワードハッシュの形式が不正です。'
])]
final class InvalidHashedPasswordException extends DomainException
{
}
