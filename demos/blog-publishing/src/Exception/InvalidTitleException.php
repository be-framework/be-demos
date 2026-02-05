<?php

declare(strict_types=1);

namespace Be\Demo\BlogPublishing\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid article title. Must be between 1 and 200 characters.',
    'ja' => '無効な記事タイトルです。1文字以上200文字以下で入力してください。'
])]
final class InvalidTitleException extends DomainException
{
}
