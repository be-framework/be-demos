<?php

declare(strict_types=1);

namespace Be\Demo\InsuranceClaim\Moment;

use Be\Demo\InsuranceClaim\Moment\Potential\DamageValuation;
use Be\Demo\InsuranceClaim\Reason\DamageAppraiserInterface;
use Ray\Di\Di\Inject;

/**
 * Damage Valued - Moment (part + potential)
 *
 * Part of ClaimSettled/ClaimEscalated, holding the potential to finalize damage valuation.
 * Born from DamageAppraiser. Holds the final valued amount.
 */
final readonly class DamageValued implements MomentInterface
{
    public DamageValuation $valuation;
    public int $valuedAmount;

    public function __construct(
        #[Inject] DamageAppraiserInterface $appraiser,
    ) {
        // Born: create potential (provisional damage valuation)
        $this->valuation = $appraiser->value();
        $this->valuedAmount = $this->valuation->amount;
    }

    public function be(): void
    {
        $this->valuation->be();
    }
}
