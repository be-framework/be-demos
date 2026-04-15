<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid property address. Please enter an address between 5 and 200 characters.',
    'ja' => '無効な物件住所です。5文字から200文字の間で入力してください。'
])]
final class InvalidPropertyAddressException extends DomainException
{
}
