<?php

declare(strict_types=1);

namespace Be\Pattern\MedicalTriage\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid consciousness level. Please enter a valid JCS value (0, 1, 2, 3, 10, 20, 30, 100, 200, 300).',
    'ja' => '無効な意識レベルです。有効なJCS値（0, 1, 2, 3, 10, 20, 30, 100, 200, 300）を入力してください。'
])]
final class InvalidConsciousnessException extends DomainException
{
}
