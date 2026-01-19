<?php

declare(strict_types=1);

namespace Be\App\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid street address. Must be between 5 and 200 characters.',
    'ja' => '無効な住所です。5文字以上200文字以内で入力してください。'
])]
final class InvalidStreetAddressException extends DomainException
{
}
