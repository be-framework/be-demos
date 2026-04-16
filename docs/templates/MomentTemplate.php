<?php

declare(strict_types=1);

namespace Be\Pattern\Template\Moment; // TODO: replace `Template` with the real demo name

use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Moment template (Keiki / 契機).
 *
 * A Moment is a part of a larger whole that carries a deferred Potential.
 * Rules:
 *   - `final readonly`, implements a MomentInterface
 *   - the constructor creates the Potential (e.g. reserves a resource)
 *   - the `be()` method commits the Potential
 *   - NEVER call `be()` from the constructor — the enclosing Final will call
 *     it during self-completion
 *
 * Canonical example: demos/order-processing/src/Moment/InventoryReserved.php
 *
 * NOTE: MomentInterface is defined per-demo under its own namespace (e.g.
 * `Be\Pattern\OrderProcessing\Moment\MomentInterface`). Re-declare or import
 * the appropriate one when you copy this template into a real demo.
 */
final readonly class MomentTemplate /* implements MomentInterface */
{
    // TODO: replace with the real Potential type from your demo.
    public object $potential;

    public function __construct(
        #[Input] public string $someField, // TODO: replace
        #[Inject] /* TODO: SomeReasonInterface */ object $reason,
    ) {
        // Born: create the Potential (e.g. lock a row, reserve a slot).
        // TODO: $this->potential = $reason->reserve($someField);
        $this->potential = new \stdClass();
    }

    public function be(): void
    {
        // Realize the Potential. Must be idempotent.
        // TODO: $this->potential->be();
    }
}
