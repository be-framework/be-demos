<?php

declare(strict_types=1);

namespace Be\Demo\LoanApplication\Moment\Potential;

use Be\Demo\LoanApplication\Moment\MomentInterface;

/**
 * Credit Inquiry - potential to finalize credit bureau inquiry
 *
 * Born from CreditBureau::inquire()
 * Realized by be() -> confirms the credit inquiry record
 */
final class CreditInquiry implements MomentInterface
{
    /** @var callable(): string */
    private $realize;

    private ?string $inquiryId = null;

    /**
     * @param callable(): string $realize
     */
    public function __construct(
        public readonly int $creditScore,
        public readonly string $creditRating,
        callable $realize,
    ) {
        $this->realize = $realize;
    }

    public function be(): void
    {
        if ($this->inquiryId !== null) {
            return; // Already realized - idempotent
        }

        $this->inquiryId = ($this->realize)();
    }

    public function getInquiryId(): ?string
    {
        return $this->inquiryId;
    }
}
