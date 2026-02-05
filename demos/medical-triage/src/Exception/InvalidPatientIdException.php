<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid patient ID. Please enter an ID in the format PT- followed by digits (e.g., PT-12345).',
    'ja' => '無効な患者IDです。PT-に続けて数字の形式で入力してください（例：PT-12345）。'
])]
final class InvalidPatientIdException extends DomainException
{
}
