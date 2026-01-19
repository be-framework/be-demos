<?php

declare(strict_types=1);

namespace Be\App\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid cart ID. Expected format: CART-XXX',
    'ja' => '無効なカートIDです。形式: CART-XXX'
])]
final class InvalidCartIdException extends DomainException
{
}
