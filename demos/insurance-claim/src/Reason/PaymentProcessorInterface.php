<?php

declare(strict_types=1);

namespace Be\Demo\InsuranceClaim\Reason;

use Be\Demo\InsuranceClaim\Moment\Potential\PaymentExecution;

/**
 * Payment Processor Interface
 *
 * Enables testability through dependency injection.
 */
interface PaymentProcessorInterface
{
    public function authorize(): PaymentExecution;
}
