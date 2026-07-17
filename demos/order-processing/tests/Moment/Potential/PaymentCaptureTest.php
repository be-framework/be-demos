<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Tests\Moment\Potential;

use Be\Pattern\OrderProcessing\Moment\Potential\PaymentCapture;
use PHPUnit\Framework\TestCase;

class PaymentCaptureTest extends TestCase
{
    public function testTransactionIdIsNullUntilRealized(): void
    {
        $capture = new PaymentCapture('AUTH-TEST', 10000, static fn (): string => 'TXN-TEST-001');

        $this->assertNull($capture->getTransactionId());
    }

    public function testBeRealizesThePotential(): void
    {
        $captured = 0;
        $capture = new PaymentCapture('AUTH-TEST', 10000, function () use (&$captured): string {
            $captured++;

            return 'TXN-TEST-001';
        });

        $capture->be();

        $this->assertSame(1, $captured);
        $this->assertSame('TXN-TEST-001', $capture->getTransactionId());
    }

    public function testBeIsIdempotent(): void
    {
        $captured = 0;
        $capture = new PaymentCapture('AUTH-TEST', 10000, function () use (&$captured): string {
            $captured++;

            return 'TXN-TEST-' . $captured;
        });

        $capture->be();
        $capture->be();

        $this->assertSame(1, $captured);
        $this->assertSame('TXN-TEST-1', $capture->getTransactionId());
    }
}
