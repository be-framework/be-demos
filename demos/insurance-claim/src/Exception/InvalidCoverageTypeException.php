<?php

declare(strict_types=1);

namespace Be\Demo\InsuranceClaim\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid coverage type. Must be one of: comprehensive, liability, medical, property.',
    'ja' => '無効な補償種別です。comprehensive, liability, medical, propertyのいずれかを指定してください。'
])]
final class InvalidCoverageTypeException extends DomainException
{
}
