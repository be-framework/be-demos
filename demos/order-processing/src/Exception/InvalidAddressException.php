<?php

declare(strict_types=1);

namespace Be\App\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid address. Please check your postal code and street address.',
    'ja' => '無効な住所です。郵便番号と住所を確認してください。'
])]
final class InvalidAddressException extends DomainException
{
}
