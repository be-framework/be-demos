<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Moment\Potential;

use Be\Pattern\LoanApplication\Moment\MomentInterface;

/**
 * Insurance Contract - potential to bind insurance policy
 *
 * Born from InsuranceQuoter::quote()
 * Realized by be() -> binds the insurance contract
 */
final class InsuranceContract implements MomentInterface
{
    /** @var callable(): string */
    private $realize;

    private ?string $policyId = null;

    /**
     * @param callable(): string $realize
     */
    public function __construct(
        public readonly int $monthlyPremium,
        public readonly int $coverageAmount,
        callable $realize,
    ) {
        $this->realize = $realize;
    }

    public function be(): void
    {
        if ($this->policyId !== null) {
            return; // Already realized - idempotent
        }

        $this->policyId = ($this->realize)();
    }

    public function getPolicyId(): ?string
    {
        return $this->policyId;
    }
}
