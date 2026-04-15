<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid incident type. Must be one of: accident, theft, fire, natural_disaster, medical.',
    'ja' => '無効な事故種別です。accident, theft, fire, natural_disaster, medicalのいずれかを指定してください。'
])]
final class InvalidIncidentTypeException extends DomainException
{
}
