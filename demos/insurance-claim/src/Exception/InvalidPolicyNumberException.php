<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid policy number. Must be in PLY-digits format.',
    'ja' => '無効な保険証券番号です。PLY-数字の形式で入力してください。'
])]
final class InvalidPolicyNumberException extends DomainException
{
}
