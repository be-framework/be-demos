<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Tests\Reason;

use Be\Pattern\OrderProcessing\Moment\Potential\ShippingDispatch;
use Be\Pattern\OrderProcessing\Reason\ShippingArranger;
use PHPUnit\Framework\TestCase;

class ShippingArrangerTest extends TestCase
{
    private ShippingArranger $arranger;

    protected function setUp(): void
    {
        $this->arranger = new ShippingArranger();
    }

    public function testPrepareYamatoReturnsMoment(): void
    {
        $dispatch = $this->arranger->prepare('YAMATO', '〒150-0001 渋谷区神宮前1-1-1');

        $this->assertInstanceOf(ShippingDispatch::class, $dispatch);
        $this->assertStringStartsWith('YAMATO-', $dispatch->trackingNumber);
        $this->assertSame(800, $dispatch->shippingRate);
    }

    public function testPrepareSagawaReturnsMoment(): void
    {
        $dispatch = $this->arranger->prepare('SAGAWA', '〒450-0001 名古屋市中区1-1-1');

        $this->assertStringStartsWith('SAGAWA-', $dispatch->trackingNumber);
        $this->assertSame(750, $dispatch->shippingRate);
    }

    public function testPrepareJapanPostReturnsMoment(): void
    {
        $dispatch = $this->arranger->prepare('JPPOST', '〒900-0001 那覇市1-1-1');

        $this->assertStringStartsWith('JPPOST-', $dispatch->trackingNumber);
        $this->assertSame(500, $dispatch->shippingRate);
    }

    public function testMomentCanBeRealized(): void
    {
        $dispatch = $this->arranger->prepare('YAMATO', '〒150-0001 渋谷区神宮前1-1-1');

        // Realize the potential
        $dispatch->be();

        // No exception means success
        $this->assertTrue(true);
    }
}
