<?php

declare(strict_types=1);

namespace Be\Pattern\Hello\Input;

use Be\Pattern\Hello\Final\Hello;
use Be\Framework\Attribute\Be;

/**
 * Input for Hello transformation
 *
 * The simplest possible Input - just a name.
 * Declares its potential to become Hello.
 */
#[Be([Hello::class])]
final readonly class HelloInput
{
    public function __construct(
        public string $name,
    ) {
    }
}
