<?php

declare(strict_types=1);

namespace Be\Pattern\BlogPublishing\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Author name cannot be empty.',
    'ja' => '著者名は空にできません。'
])]
final class InvalidAuthorNameException extends DomainException
{
}
