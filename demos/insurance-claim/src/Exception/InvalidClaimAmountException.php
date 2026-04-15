<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid claim amount. Must be a positive integer up to 100,000,000.',
    'ja' => '無効な請求金額です。1から100,000,000までの正の整数を入力してください。'
])]
final class InvalidClaimAmountException extends DomainException
{
}
