<?php

declare(strict_types=1);

namespace Be\App\Tests\Reason;

use Be\App\Reason\CarrierSelector;
use PHPUnit\Framework\TestCase;

class CarrierSelectorTest extends TestCase
{
    private CarrierSelector $selector;

    protected function setUp(): void
    {
        $this->selector = new CarrierSelector();
    }

    public function testSelectYamato(): void
    {
        $carrier = $this->selector->select('1500001');
        $this->assertSame('YAMATO', $carrier['id']);
        $this->assertSame('Yamato Transport', $carrier['name']);
    }

    public function testSelectSagawa(): void
    {
        $carrier = $this->selector->select('4500001');
        $this->assertSame('SAGAWA', $carrier['id']);
        $this->assertSame('Sagawa Express', $carrier['name']);
    }

    public function testSelectJapanPost(): void
    {
        $carrier = $this->selector->select('9000001');
        $this->assertSame('JPPOST', $carrier['id']);
        $this->assertSame('Japan Post', $carrier['name']);
    }
}
