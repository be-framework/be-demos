<?php

declare(strict_types=1);

namespace Be\App\Tests\Reason;

use Be\App\Reason\WarehouseLocator;
use PHPUnit\Framework\TestCase;

class WarehouseLocatorTest extends TestCase
{
    private WarehouseLocator $locator;

    protected function setUp(): void
    {
        $this->locator = new WarehouseLocator();
    }

    public function testLocateProductA(): void
    {
        $this->assertSame('WH-TOKYO-01', $this->locator->locate('A001'));
    }

    public function testLocateProductB(): void
    {
        $this->assertSame('WH-OSAKA-01', $this->locator->locate('B002'));
    }

    public function testLocateProductDefault(): void
    {
        $this->assertSame('WH-CENTRAL-01', $this->locator->locate('C003'));
    }
}
