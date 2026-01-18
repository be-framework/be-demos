<?php

declare(strict_types=1);

namespace Be\App\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid amount. Must be between 1 and 100,000,000.',
    'ja' => '無効な金額です。1円以上1億円以下で入力してください。'
])]
final class InvalidAmountException extends DomainException
{
}
