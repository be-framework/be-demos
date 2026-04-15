<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Reason;

use Be\Pattern\InsuranceClaim\Moment\Potential\PaymentExecution;

/**
 * Payment Processor Interface
 *
 * Enables testability through dependency injection.
 */
interface PaymentProcessorInterface
{
    public function authorize(): PaymentExecution;
}
