<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Final;

use Be\Pattern\InsuranceClaim\Moment\AdjustmentReviewed;
use Be\Pattern\InsuranceClaim\Moment\DamageValued;
use Be\Pattern\InsuranceClaim\Moment\EscalationQueued;
use Be\Pattern\InsuranceClaim\Moment\FraudCleared;
use Ray\Di\Di\Inject;

/**
 * Claim Escalated - Final (convergence point for escalation path)
 *
 * Complex metamorphosis: four Moments converge into one Final.
 * Only DamageValued is realized (no payment in escalation path).
 * EscalationQueued is pure data (no potential).
 *
 * Path: amount > threshold (requires manual review)
 */
final readonly class ClaimEscalated
{
    public string $escalationNumber;
    public string $reason;
    public string $escalatedAt;

    public function __construct(
        #[Inject] public DamageValued $damageValued,
        #[Inject] public AdjustmentReviewed $adjustmentReviewed,
        #[Inject] public FraudCleared $fraudCleared,
        #[Inject] public EscalationQueued $escalationQueued,
    ) {
        // Self-completion: realize only DamageValued (no payment in escalation)
        $this->damageValued->be();

        $this->reason = sprintf(
            'Amount %d exceeds auto-approval threshold; damage grade: %s',
            $this->damageValued->valuedAmount,
            'assessed'
        );
        $this->escalationNumber = $this->generateEscalationNumber();
        $this->escalatedAt = date('Y-m-d\TH:i:sP');
    }

    private function generateEscalationNumber(): string
    {
        return sprintf(
            'ESC-%s-%s',
            date('Ymd'),
            substr(md5(
                $this->escalationQueued->escalationId .
                ($this->damageValued->valuation->getValuationId() ?? '')
            ), 0, 8)
        );
    }
}
