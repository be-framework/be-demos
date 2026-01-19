<?php

declare(strict_types=1);

namespace Be\App\Tests\Being\Inventory;

use Be\App\Being\Inventory\StockLocated;
use Be\App\Reason\WarehouseLocator;
use PHPUnit\Framework\TestCase;

class StockLocatedTest extends TestCase
{
    public function testStockLocatedTokyo(): void
    {
        $locator = new WarehouseLocator();
        $being = new StockLocated('A001', $locator);

        $this->assertSame('A001', $being->productId);
        $this->assertSame('WH-TOKYO-01', $being->warehouseId);
    }

    public function testStockLocatedOsaka(): void
    {
        $locator = new WarehouseLocator();
        $being = new StockLocated('B002', $locator);

        $this->assertSame('B002', $being->productId);
        $this->assertSame('WH-OSAKA-01', $being->warehouseId);
    }

    public function testStockLocatedCentral(): void
    {
        $locator = new WarehouseLocator();
        $being = new StockLocated('C003', $locator);

        $this->assertSame('C003', $being->productId);
        $this->assertSame('WH-CENTRAL-01', $being->warehouseId);
    }
}
