<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Moment\Potential;

use Be\Pattern\LoanApplication\Moment\MomentInterface;

/**
 * Collateral Registration - potential to register property as collateral
 *
 * Born from PropertyAppraisal::appraise()
 * Realized by be() -> registers the collateral
 */
final class CollateralRegistration implements MomentInterface
{
    /** @var callable(): string */
    private $realize;

    private ?string $registrationId = null;

    /**
     * @param callable(): string $realize
     */
    public function __construct(
        public readonly int $appraisedValue,
        public readonly string $propertyAddress,
        callable $realize,
    ) {
        $this->realize = $realize;
    }

    public function be(): void
    {
        if ($this->registrationId !== null) {
            return; // Already realized - idempotent
        }

        $this->registrationId = ($this->realize)();
    }

    public function getRegistrationId(): ?string
    {
        return $this->registrationId;
    }
}
