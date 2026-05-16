<?php

declare(strict_types=1);

namespace Be\Pattern\UserRegistration\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid avatar URL.',
    'ja' => 'アバターURLの形式が不正です。'
])]
final class InvalidAvatarUrlException extends DomainException
{
}
