<?php

declare(strict_types=1);

namespace Be\Pattern\ContactForm\Context;

use Koriym\SemanticLogger\AbstractContext;

final class ReceiptGeneratedContext extends AbstractContext
{
    public const string TYPE = 'receipt_generated';
    public const string SCHEMA_URL = 'https://be-framework.github.io/schemas/contact-form/receipt-generated.json';

    public function __construct(
        public readonly string $receiptId,
        public readonly string $email,
        public readonly string $receivedAt,
    ) {
    }
}
