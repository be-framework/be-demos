<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid blood pressure. Systolic must be 40-300 mmHg, diastolic must be 20-200 mmHg.',
    'ja' => '無効な血圧です。収縮期血圧は40〜300mmHg、拡張期血圧は20〜200mmHgの範囲で入力してください。'
])]
final class InvalidBloodPressureException extends DomainException
{
}
