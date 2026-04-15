<?php

declare(strict_types=1);

namespace Be\Pattern\Template\Input; // TODO: replace `Template` with the real demo name (or use Be\App\Input)

use Be\Framework\Attribute\Be;

/**
 * Input template (Dynamis / δύναμις).
 *
 * An Input is the raw potential entering the system. It MUST:
 *   - be `final readonly`
 *   - declare its successor via `#[Be([Next::class])]`
 *   - hold only user-supplied data in typed public properties
 *
 * Canonical example: demos/hello-world/src/Input/HelloInput.php
 */
#[Be([/* TODO: NextBeingOrFinal::class */])]
final readonly class InputTemplate
{
    public function __construct(
        // TODO: replace with the real user-supplied fields.
        public string $exampleField,
    ) {
    }
}
