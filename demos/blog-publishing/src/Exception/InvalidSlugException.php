<?php

declare(strict_types=1);

namespace Be\Pattern\BlogPublishing\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Invalid slug format. Use lowercase letters, digits, and hyphens only.',
    'ja' => 'スラッグの形式が不正です。小文字・数字・ハイフンのみ使用できます。'
])]
final class InvalidSlugException extends DomainException
{
}
