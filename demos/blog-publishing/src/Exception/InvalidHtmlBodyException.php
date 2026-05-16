<?php

declare(strict_types=1);

namespace Be\Pattern\BlogPublishing\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'HTML body cannot be empty.',
    'ja' => 'HTML本文は空にできません。'
])]
final class InvalidHtmlBodyException extends DomainException
{
}
