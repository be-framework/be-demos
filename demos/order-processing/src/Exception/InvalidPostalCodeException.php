<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid postal code. Please enter a 7-digit postal code.',
    'ja' => '無効な郵便番号です。7桁の郵便番号を入力してください。'
])]
final class InvalidPostalCodeException extends DomainException
{
}
