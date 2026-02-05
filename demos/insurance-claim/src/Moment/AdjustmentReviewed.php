<?php

declare(strict_types=1);

namespace Be\Demo\InsuranceClaim\Moment;

use Ray\Di\Di\Inject;
use Be\Demo\InsuranceClaim\Reason\AdjusterAllocatorInterface;

/**
 * Adjustment Reviewed - Moment (pure data, no Potential)
 *
 * Part of ClaimSettled/ClaimEscalated. Records the adjuster's review outcome.
 * Pure data moment - does not implement MomentInterface as it has no potential to realize.
 */
final readonly class AdjustmentReviewed
{
    public string $adjusterId;
    public string $reviewNotes;

    public function __construct(
        #[Inject] AdjusterAllocatorInterface $allocator,
    ) {
        $result = $allocator->review();
        $this->adjusterId = $result['adjusterId'];
        $this->reviewNotes = $result['reviewNotes'];
    }
}
