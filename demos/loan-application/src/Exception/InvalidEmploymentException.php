<?php

declare(strict_types=1);

namespace Be\Demo\LoanApplication\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid employment years. Please enter a value between 0 and 50.',
    'ja' => '無効な勤続年数です。0から50の間で入力してください。'
])]
final class InvalidEmploymentException extends DomainException
{
}
