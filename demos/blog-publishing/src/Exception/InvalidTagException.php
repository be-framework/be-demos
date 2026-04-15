<?php

declare(strict_types=1);

namespace Be\Pattern\BlogPublishing\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid tag. Must be non-empty, lowercase alphanumeric with hyphens, max 30 characters.',
    'ja' => '無効なタグです。空でない小文字英数字とハイフンのみ、30文字以下で入力してください。'
])]
final class InvalidTagException extends DomainException
{
}
