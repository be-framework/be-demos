<?php

declare(strict_types=1);

namespace Be\Pattern\UserRegistration\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Email address already registered.',
    'ja' => 'このメールアドレスは既に登録されています。'
])]
final class DuplicateEmailException extends DomainException
{
}
