<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Tests\Moment\Potential;

use Be\Pattern\OrderProcessing\Moment\Potential\ShippingDispatch;
use PHPUnit\Framework\TestCase;

class ShippingDispatchTest extends TestCase
{
    public function testBeRealizesThePotential(): void
    {
        $dispatched = 0;
        $dispatch = new ShippingDispatch('TRK-TEST-001', 800, function () use (&$dispatched): void {
            $dispatched++;
        });

        $dispatch->be();

        $this->assertSame(1, $dispatched);
    }

    public function testBeIsIdempotent(): void
    {
        $dispatched = 0;
        $dispatch = new ShippingDispatch('TRK-TEST-001', 800, function () use (&$dispatched): void {
            $dispatched++;
        });

        $dispatch->be();
        $dispatch->be();

        $this->assertSame(1, $dispatched);
    }
}
