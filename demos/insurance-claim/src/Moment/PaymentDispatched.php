<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Moment;

use Be\Pattern\InsuranceClaim\Moment\Potential\PaymentExecution;
use Be\Pattern\InsuranceClaim\Reason\PaymentProcessorInterface;
use Ray\Di\Di\Inject;

/**
 * Payment Dispatched - Moment (part + potential)
 *
 * Part of ClaimSettled, holding the potential to execute payment.
 * Only used in the settlement path (amount <= threshold).
 */
final readonly class PaymentDispatched implements MomentInterface
{
    public PaymentExecution $execution;

    public function __construct(
        #[Inject] PaymentProcessorInterface $processor,
    ) {
        // Born: create potential (authorize payment dispatch)
        $this->execution = $processor->authorize();
    }

    public function be(): void
    {
        $this->execution->be();
    }
}
