<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Final;

use Be\Pattern\InsuranceClaim\Moment\AdjustmentReviewed;
use Be\Pattern\InsuranceClaim\Moment\DamageValued;
use Be\Pattern\InsuranceClaim\Moment\FraudCleared;
use Be\Pattern\InsuranceClaim\Moment\PaymentDispatched;
use Ray\Di\Di\Inject;

/**
 * Claim Settled - Final (convergence point for settlement path)
 *
 * Complex metamorphosis: four Moments converge into one Final.
 * DamageValued and PaymentDispatched are realized through self-completion.
 * AdjustmentReviewed and FraudCleared are pure data (no potential).
 *
 * Path: amount <= threshold (auto-approved settlement)
 */
final readonly class ClaimSettled
{
    public string $settlementId;
    public int $paidAmount;
    public string $settledAt;

    public function __construct(
        #[Inject] public DamageValued $damageValued,
        #[Inject] public AdjustmentReviewed $adjustmentReviewed,
        #[Inject] public FraudCleared $fraudCleared,
        #[Inject] public PaymentDispatched $paymentDispatched,
    ) {
        // Self-completion: realize Moments with potential
        $this->damageValued->be();
        $this->paymentDispatched->be();

        $this->paidAmount = $this->damageValued->valuedAmount;
        $this->settlementId = $this->generateSettlementId();
        $this->settledAt = date('Y-m-d\TH:i:sP');
    }

    private function generateSettlementId(): string
    {
        return sprintf(
            'STL-%s-%s',
            date('Ymd'),
            substr(md5(
                ($this->damageValued->valuation->getValuationId() ?? '') .
                ($this->paymentDispatched->execution->getTransactionId() ?? '')
            ), 0, 8)
        );
    }
}
