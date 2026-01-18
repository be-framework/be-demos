<?php

declare(strict_types=1);

namespace Be\App\Moment\Potential;

use Be\App\Moment\MomentInterface;

/**
 * Payment Capture - potential to capture authorized payment
 *
 * Born from PaymentGateway::authorize()
 * Realized by be() → captures the payment
 */
final class PaymentCapture implements MomentInterface
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
        $this->transactionId = ($this->realize)();
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }
}
