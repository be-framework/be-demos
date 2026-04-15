<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Reason;

/**
 * Settlement Policy - Reason for determining auto-approval
 *
 * Determines whether a claim amount falls within the auto-approval threshold.
 */
final class SettlementPolicy
{
    private const AUTO_APPROVE_THRESHOLD = 1_000_000;

    public function isAutoApprovable(int $assessedAmount): bool
    {
        return $assessedAmount <= self::AUTO_APPROVE_THRESHOLD;
    }

    public function getThreshold(): int
    {
        return self::AUTO_APPROVE_THRESHOLD;
    }
}
