<?php

declare(strict_types=1);

namespace Be\Template;

use Be\Framework\Attribute\Be;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Being template (Dasein).
 *
 * A Being is an existential state whose properties are derived at
 * construction time from its `#[Input]` parameters and injected Reason
 * services. Rules:
 *   - `final readonly`
 *   - `#[Be([Next::class])]` declares the successor (terminal Beings point
 *     directly at a Final)
 *   - `#[Input]` parameters come FIRST, `#[Inject]` parameters come LAST
 *   - no side effects; pure transformation only
 *
 * Canonical example: demos/contact-form/src/Being/EmailNormalized.php
 */
#[Be([/* TODO: NextBeingOrFinal::class */])]
final readonly class BeingTemplate
{
    // TODO: add any computed properties this Being exposes to its successor.
    public string $computedField;

    public function __construct(
        // Inputs from the previous state (public properties are propagated).
        #[Input] public string $rawField, // TODO: replace
        // Reasons injected by Ray.Di — always depend on an interface.
        #[Inject] /* TODO: SomeReasonInterface */ object $reason,
    ) {
        // TODO: derive the computed fields via the injected Reason service.
        $this->computedField = $rawField;
    }
}
