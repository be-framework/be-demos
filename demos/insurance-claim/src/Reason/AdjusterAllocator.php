<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Reason;

/**
 * Adjuster Allocator - Reason (stateless gateway)
 *
 * Allocates adjusters to claims and records their reviews.
 */
final class AdjusterAllocator implements AdjusterAllocatorInterface
{
    private string $lastAdjusterId = '';

    /**
     * @return array{adjusterId: string, assignedAt: string}
     */
    public function allocate(string $claimNumber, string $incidentType): array
    {
        // Demo: assign adjuster based on incident type
        $adjusterId = sprintf(
            'ADJ-%s-%d',
            strtoupper(substr($incidentType, 0, 3)),
            random_int(100, 999)
        );
        $this->lastAdjusterId = $adjusterId;

        return [
            'adjusterId' => $adjusterId,
            'assignedAt' => date('Y-m-d\TH:i:sP'),
        ];
    }

    /**
     * @return array{adjusterId: string, reviewNotes: string}
     */
    public function review(): array
    {
        return [
            'adjusterId' => $this->lastAdjusterId ?: 'ADJ-DEFAULT-001',
            'reviewNotes' => 'Claim reviewed and assessed per standard procedure.',
        ];
    }
}
