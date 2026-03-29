<?php

declare(strict_types=1);

namespace Be\Demo\LoanApplication\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid loan amount. Please enter an amount between 1,000,000 and 200,000,000.',
    'ja' => '無効な融資額です。100万円から2億円の範囲で入力してください。'
])]
final class InvalidLoanAmountException extends DomainException
{
}
