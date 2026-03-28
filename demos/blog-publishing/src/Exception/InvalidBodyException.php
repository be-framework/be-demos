<?php

declare(strict_types=1);

namespace Be\Demo\BlogPublishing\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid article body. Must be between 50 and 50,000 characters.',
    'ja' => '無効な記事本文です。50文字以上50,000文字以下で入力してください。'
])]
final class InvalidBodyException extends DomainException
{
}
