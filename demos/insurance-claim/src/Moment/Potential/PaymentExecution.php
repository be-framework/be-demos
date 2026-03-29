<?php

declare(strict_types=1);

namespace Be\Demo\InsuranceClaim\Moment\Potential;

use Be\Demo\InsuranceClaim\Moment\MomentInterface;

/**
 * Payment Execution - potential to execute claim payment
 *
 * Born from PaymentProcessor::authorize()
 * Realized by be() -> executes the payment
 */
final class PaymentExecution implements MomentInterface
{
    /** @var callable(): string */
    private $realize;

    private ?string $transactionId = null;

    /**
     * @param callable(): string $realize
     */
    public function __construct(
        public readonly string $authorizationCode,
        public readonly int $amount,
        callable $realize,
    ) {
        $this->realize = $realize;
    }

    public function be(): void
    {
        if ($this->transactionId !== null) {
            return; // Already realized - idempotent
        }

        $this->transactionId = ($this->realize)();
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }
}
