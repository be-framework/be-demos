<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid CVV. Must be 3 or 4 digits.',
    'ja' => '無効なCVVです。3桁または4桁の数字で入力してください。'
])]
final class InvalidCardCvvException extends DomainException
{
}
