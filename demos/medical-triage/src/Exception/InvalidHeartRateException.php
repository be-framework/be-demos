<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid heart rate. Please enter a value between 0 and 300 bpm.',
    'ja' => '無効な心拍数です。0〜300bpmの範囲で入力してください。'
])]
final class InvalidHeartRateException extends DomainException
{
}
