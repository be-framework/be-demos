<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid warehouse ID. Expected format: WH-XXX',
    'ja' => '無効な倉庫IDです。形式: WH-XXX'
])]
final class InvalidWarehouseIdException extends DomainException
{
}
