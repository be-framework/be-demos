<?php

declare(strict_types=1);

namespace Be\Pattern\BlogPublishing\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Excerpt is too long. Must be 250 characters or fewer.',
    'ja' => '抜粋が長すぎます。250文字以下で入力してください。'
])]
final class InvalidExcerptException extends DomainException
{
}
