<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Reason;

/**
 * Adjuster Allocator Interface
 *
 * Enables testability through dependency injection.
 */
interface AdjusterAllocatorInterface
{
    /**
     * @return array{adjusterId: string, assignedAt: string}
     */
    public function allocate(string $claimNumber, string $incidentType): array;

    /**
     * @return array{adjusterId: string, reviewNotes: string}
     */
    public function review(): array;
}
