<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Being\Inventory;

use Ray\InputQuery\Attribute\Input;
use Ray\Di\Di\Inject;
use Be\Pattern\OrderProcessing\Reason\InventoryChecker;

final readonly class QuantityChecked
{
    public bool $available;

    public function __construct(
        #[Input] public string $productId,
        #[Input] public int $quantity,
        #[Input] public string $warehouseId,
        #[Inject] InventoryChecker $checker
    ) {
        $this->available = $checker->check($warehouseId, $productId, $quantity);
    }
}
