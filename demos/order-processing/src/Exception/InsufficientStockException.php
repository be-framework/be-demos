<?php

declare(strict_types=1);

namespace Be\App\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Insufficient stock available.',
    'ja' => '在庫が不足しています。'
])]
final class InsufficientStockException extends DomainException
{
}
