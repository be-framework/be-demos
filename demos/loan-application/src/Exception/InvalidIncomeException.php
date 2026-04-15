<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid annual income. Please enter a positive amount up to 1 billion.',
    'ja' => '無効な年収です。1から10億円の範囲で入力してください。'
])]
final class InvalidIncomeException extends DomainException
{
}
