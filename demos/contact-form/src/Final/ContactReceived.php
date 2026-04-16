<?php

declare(strict_types=1);

namespace Be\Pattern\ContactForm\Final;

use Be\Framework\SemanticLog\Been;
use Be\Pattern\ContactForm\Context\ReceiptGeneratedContext;
use Be\Pattern\ContactForm\Reason\ReceiptGenerator;
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
    public Been $been;

    public function __construct(
        #[Input] public string $name,
        #[Input] public string $subject,
        #[Input] public string $message,
        #[Input] public string $normalizedEmail,
        #[Inject] ReceiptGenerator $receipt,
        #[Inject] Been $been,
    ) {
        $this->receiptId = $receipt->generate();
        $this->receivedAt = date('Y-m-d\TH:i:sP');
        $this->been = $been->with(new ReceiptGeneratedContext(
            receiptId: $this->receiptId,
            email: $normalizedEmail,
            receivedAt: $this->receivedAt,
        ));

        // Proof: the receipt was generated for this email
        $event = $this->been->events[0];
        assert($event instanceof ReceiptGeneratedContext);
        assert($event->email === $this->normalizedEmail, 'Receipt must be for the normalized email');
    }
}
