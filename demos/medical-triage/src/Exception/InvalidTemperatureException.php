<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid temperature. Please enter a value between 30.0 and 45.0 degrees Celsius.',
    'ja' => '無効な体温です。30.0〜45.0度の範囲で入力してください。'
])]
final class InvalidTemperatureException extends DomainException
{
}
