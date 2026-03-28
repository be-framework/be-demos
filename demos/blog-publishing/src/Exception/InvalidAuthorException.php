<?php

declare(strict_types=1);

namespace Be\Demo\BlogPublishing\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid author ID. Must be a valid UUID format.',
    'ja' => '無効な著者IDです。有効なUUID形式で入力してください。'
])]
final class InvalidAuthorException extends DomainException
{
}
