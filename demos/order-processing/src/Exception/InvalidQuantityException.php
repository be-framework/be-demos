<?php

declare(strict_types=1);

namespace Be\App\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid quantity. Please enter a quantity between 1 and 99.',
    'ja' => '無効な数量です。1から99の間で入力してください。'
])]
final class InvalidQuantityException extends DomainException
{
}
