<?php

declare(strict_types=1);

namespace Be\App\Tests\Moment;

use Be\App\Moment\ShippingArranged;
use Be\App\Reason\ShippingArranger;
use PHPUnit\Framework\TestCase;

class ShippingArrangedTest extends TestCase
{
    public function testShippingArrangedYamato(): void
    {
        $arranger = new ShippingArranger();
        $moment = new ShippingArranged('YAMATO', '〒150-0001 渋谷区神宮前1-1-1', $arranger);

        $this->assertSame('YAMATO', $moment->carrierId);
        $this->assertStringStartsWith('YAMATO-', $moment->dispatch->trackingNumber);
        $this->assertSame(800, $moment->dispatch->shippingRate);
    }

    public function testShippingArrangedSagawa(): void
    {
        $arranger = new ShippingArranger();
        $moment = new ShippingArranged('SAGAWA', '〒450-0001 名古屋市中区1-1-1', $arranger);

        $this->assertStringStartsWith('SAGAWA-', $moment->dispatch->trackingNumber);
        $this->assertSame(750, $moment->dispatch->shippingRate);
    }

    public function testMomentCanBeRealized(): void
    {
        $arranger = new ShippingArranger();
        $moment = new ShippingArranged('YAMATO', '〒150-0001 渋谷区神宮前1-1-1', $arranger);

        // Realize the Moment
        $moment->be();

        // No exception means success
        $this->assertTrue(true);
    }
}
