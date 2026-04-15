<?php

declare(strict_types=1);

namespace Be\Pattern\Hello\Final;

use Be\Pattern\Hello\Reason\Greeting;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Hello - Final state
 *
 * The actualization of HelloInput's potential.
 * Combines the input name with a greeting from Reason.
 */
final readonly class Hello
{
    public string $greeting;

    public function __construct(
        #[Input] string $name,
        #[Inject] Greeting $greeting,
    ) {
        $this->greeting = "{$greeting->greeting} {$name}";
    }
}
