<?php

declare(strict_types=1);

namespace Be\Demo\Template\Final; // TODO: replace `Template` with the real demo name (or use Be\App\Final)

use Ray\Di\Di\Inject;

/**
 * Final template (Energeia / ἐνέργεια).
 *
 * A Final is the fully actualized convergence point of a flow. When it owns
 * Moments it realizes them through self-completion:
 *   1. Each Moment is injected via `#[Inject]`.
 *   2. The constructor calls `$moment->be()` on each one.
 *   3. The actualized fields (ids, status, totals) are then derived from
 *      the now-committed Moment state.
 *
 * Rules:
 *   - `final readonly`
 *   - NO `#[Be]` attribute (a Final has no successor)
 *   - link to schema.org in the docblock when a standard term exists
 *
 * Canonical example: demos/order-processing/src/Final/OrderConfirmed.php
 *
 * @link https://schema.org/ — TODO: replace with the matching concept
 */
final readonly class FinalTemplate
{
    public string $id;
    public string $status;

    public function __construct(
        // TODO: inject every Moment that belongs to this Final.
        #[Inject] public /* TODO: SomeMoment */ object $someMoment,
    ) {
        // Self-completion: realize each Moment.
        // TODO: $this->someMoment->be();

        // Derive actualized fields from committed Moment state.
        $this->id = 'TODO-generate';
        $this->status = 'confirmed';
    }
}
