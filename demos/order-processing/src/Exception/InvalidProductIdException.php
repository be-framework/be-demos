<?php

declare(strict_types=1);

namespace Be\App\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid product ID. Expected format: PROD-XXX',
    'ja' => '無効な商品IDです。形式: PROD-XXX'
])]
final class InvalidProductIdException extends DomainException
{
}
