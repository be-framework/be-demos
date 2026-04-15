<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid incident date. Must be in Y-m-d format and in the past.',
    'ja' => '無効な事故日です。Y-m-d形式で過去の日付を入力してください。'
])]
final class InvalidIncidentDateException extends DomainException
{
}
