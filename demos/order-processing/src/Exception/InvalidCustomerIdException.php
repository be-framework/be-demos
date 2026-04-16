<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid customer ID. Expected format: CUST-XXX',
    'ja' => '無効な顧客IDです。形式: CUST-XXX'
])]
final class InvalidCustomerIdException extends DomainException
{
}
