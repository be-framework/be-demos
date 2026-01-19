<?php

declare(strict_types=1);

namespace Be\App\Tests\Being\Shipping;

use Be\App\Being\Shipping\AddressValidated;
use Be\App\Reason\AddressValidator;
use PHPUnit\Framework\TestCase;

class AddressValidatedTest extends TestCase
{
    public function testAddressValid(): void
    {
        $validator = new AddressValidator();
        $being = new AddressValidated('1500001', '渋谷区神宮前1-1-1', $validator);

        $this->assertSame('1500001', $being->postalCode);
        $this->assertSame('渋谷区神宮前1-1-1', $being->streetAddress);
        $this->assertTrue($being->valid);
        $this->assertSame('〒150-0001 渋谷区神宮前1-1-1', $being->normalizedAddress);
    }

    public function testAddressInvalid(): void
    {
        $validator = new AddressValidator();
        $being = new AddressValidated('', '渋谷区神宮前1-1-1', $validator);

        $this->assertFalse($being->valid);
    }
}
