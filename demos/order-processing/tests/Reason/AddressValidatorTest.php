<?php

declare(strict_types=1);

namespace Be\App\Tests\Reason;

use Be\App\Reason\AddressValidator;
use PHPUnit\Framework\TestCase;

class AddressValidatorTest extends TestCase
{
    private AddressValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new AddressValidator();
    }

    public function testValidAddress(): void
    {
        $this->assertTrue($this->validator->validate('1500001', '渋谷区神宮前1-1-1'));
    }

    public function testInvalidEmptyPostalCode(): void
    {
        $this->assertFalse($this->validator->validate('', '渋谷区神宮前1-1-1'));
    }

    public function testInvalidEmptyStreetAddress(): void
    {
        $this->assertFalse($this->validator->validate('1500001', ''));
    }

    public function testNormalizeAddress(): void
    {
        $normalized = $this->validator->normalize('1500001', '渋谷区神宮前1-1-1');
        $this->assertSame('〒150-0001 渋谷区神宮前1-1-1', $normalized);
    }

    public function testNormalizeAddressWithHyphen(): void
    {
        $normalized = $this->validator->normalize('150-0001', '渋谷区神宮前1-1-1');
        $this->assertSame('〒150-0001 渋谷区神宮前1-1-1', $normalized);
    }
}
