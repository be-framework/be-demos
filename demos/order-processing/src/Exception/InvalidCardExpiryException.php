<?php

declare(strict_types=1);

namespace Be\App\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid card expiry date. Expected format: MM/YY and must not be expired.',
    'ja' => '無効な有効期限です。形式: MM/YY で、期限切れでないこと。'
])]
final class InvalidCardExpiryException extends DomainException
{
}
