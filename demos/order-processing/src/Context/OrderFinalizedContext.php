<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Context;

use Koriym\SemanticLogger\AbstractContext;

final class OrderFinalizedContext extends AbstractContext
{
    public const string TYPE = 'order_finalized';
    public const string SCHEMA_URL = 'https://be-framework.github.io/schemas/order-processing/order-finalized.json';

    public function __construct(
        public readonly string $orderId,
        public readonly string $status,
    ) {
    }
}
