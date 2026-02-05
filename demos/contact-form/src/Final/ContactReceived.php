<?php

declare(strict_types=1);

namespace Be\Demo\ContactForm\Final;

use Be\Demo\ContactForm\Reason\ReceiptGenerator;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * ContactReceived - Final state
 *
 * The actualization of ContactInput's potential.
 * Combines input data with normalized email and generates a receipt.
 */
final readonly class ContactReceived
{
    public string $receiptId;
    public string $receivedAt;

    public function __construct(
        #[Input] public string $name,
        #[Input] public string $subject,
        #[Input] public string $message,
        #[Input] public string $normalizedEmail,
        #[Inject] ReceiptGenerator $receipt,
    ) {
        $this->receiptId = $receipt->generate();
        $this->receivedAt = date('Y-m-d\TH:i:sP');
    }
}
