<?php

declare(strict_types=1);

namespace Be\Demo\InsuranceClaim\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid claimant ID. Must be in CLM-alphanumeric format.',
    'ja' => '無効な請求者IDです。CLM-英数字の形式で入力してください。'
])]
final class InvalidClaimantIdException extends DomainException
{
}
