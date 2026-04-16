<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Tests\Being\Shipping;

use Be\Pattern\OrderProcessing\Being\Shipping\CarrierSelected;
use Be\Pattern\OrderProcessing\Reason\CarrierSelector;
use PHPUnit\Framework\TestCase;

class CarrierSelectedTest extends TestCase
{
    public function testCarrierYamato(): void
    {
        $selector = new CarrierSelector();
        $being = new CarrierSelected('1500001', $selector);

        $this->assertSame('1500001', $being->postalCode);
        $this->assertSame('YAMATO', $being->carrierId);
        $this->assertSame('Yamato Transport', $being->carrierName);
    }

    public function testCarrierSagawa(): void
    {
        $selector = new CarrierSelector();
        $being = new CarrierSelected('4500001', $selector);

        $this->assertSame('SAGAWA', $being->carrierId);
        $this->assertSame('Sagawa Express', $being->carrierName);
    }

    public function testCarrierJapanPost(): void
    {
        $selector = new CarrierSelector();
        $being = new CarrierSelected('9000001', $selector);

        $this->assertSame('JPPOST', $being->carrierId);
        $this->assertSame('Japan Post', $being->carrierName);
    }
}
