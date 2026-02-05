<?php

declare(strict_types=1);

namespace Be\Demo\InsuranceClaim\Moment\Potential;

use Be\Demo\InsuranceClaim\Moment\MomentInterface;

/**
 * Damage Valuation - potential to finalize damage assessment
 *
 * Born from DamageAppraiser::value()
 * Realized by be() -> confirms the damage valuation
 */
final class DamageValuation implements MomentInterface
{
    /** @var callable(): void */
    private $realize;

    private ?string $valuationId = null;

    /**
     * @param callable(): string $realize
     */
    public function __construct(
        public readonly int $amount,
        callable $realize,
    ) {
        $this->realize = $realize;
    }

    public function be(): void
    {
        if ($this->valuationId !== null) {
            return; // Already realized - idempotent
        }

        $this->valuationId = ($this->realize)();
    }

    public function getValuationId(): ?string
    {
        return $this->valuationId;
    }
}
