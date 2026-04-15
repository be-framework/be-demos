<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Moment;

/**
 * Escalation Queued - Moment (pure data, no Potential)
 *
 * Part of ClaimEscalated. Records escalation queue entry.
 * Pure data moment - does not implement MomentInterface as it has no potential to realize.
 * Used in the escalation path (amount > threshold).
 */
final readonly class EscalationQueued
{
    public function __construct(
        public string $escalationId,
        public string $priority,
    ) {
    }
}
