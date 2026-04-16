<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Being\Inventory;

use Ray\InputQuery\Attribute\Input;
use Ray\Di\Di\Inject;
use Be\Pattern\OrderProcessing\Reason\WarehouseLocator;

final readonly class StockLocated
{
    public string $warehouseId;

    public function __construct(
        #[Input] public string $productId,
        #[Inject] WarehouseLocator $locator
    ) {
        $this->warehouseId = $locator->locate($productId);
    }
}
