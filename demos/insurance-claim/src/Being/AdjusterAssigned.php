<?php

declare(strict_types=1);

namespace Be\Demo\InsuranceClaim\Being;

use Ray\InputQuery\Attribute\Input;
use Ray\Di\Di\Inject;
use Be\Demo\InsuranceClaim\Reason\AdjusterAllocatorInterface;

/**
 * Adjuster Assigned - Being (3-way parallel, branch 2)
 *
 * Allocates an adjuster to handle the claim.
 */
final readonly class AdjusterAssigned
{
    public string $adjusterId;
    public string $assignedAt;

    public function __construct(
        #[Input] public string $claimNumber,
        #[Input] public string $incidentType,
        #[Inject] AdjusterAllocatorInterface $allocator
    ) {
        $result = $allocator->allocate($claimNumber, $incidentType);
        $this->adjusterId = $result['adjusterId'];
        $this->assignedAt = $result['assignedAt'];
    }
}
