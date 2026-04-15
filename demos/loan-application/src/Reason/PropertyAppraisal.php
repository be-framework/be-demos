<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Reason;

use Be\Pattern\LoanApplication\Moment\Potential\CollateralRegistration;

/**
 * Property Appraisal - Reason (stateless gateway)
 *
 * Appraises property value and creates CollateralRegistration moments that can be realized.
 */
final class PropertyAppraisal implements PropertyAppraisalInterface
{
    private int $lastAppraisedValue = 0;
    private string $lastAddress = '';

    public function appraise(string $propertyAddress, string $propertyType): int
    {
        // Demo: estimate value based on property type
        $baseValue = match ($propertyType) {
            'house' => 50000000,       // 50 million yen
            'apartment' => 35000000,   // 35 million yen
            'condo' => 40000000,       // 40 million yen
            'land' => 30000000,        // 30 million yen
            default => 25000000,       // 25 million yen
        };

        // Adjust by address hash for variation
        $adjustment = (crc32($propertyAddress) % 20 - 10) * 100000;
        $this->lastAppraisedValue = $baseValue + $adjustment;
        $this->lastAddress = $propertyAddress;

        return $this->lastAppraisedValue;
    }

    public function register(): CollateralRegistration
    {
        $value = $this->lastAppraisedValue ?: 40000000;
        $address = $this->lastAddress ?: 'unknown';

        return new CollateralRegistration(
            $value,
            $address,
            fn () => $this->finalizeRegistration($value, $address),
        );
    }

    private function finalizeRegistration(int $value, string $address): string
    {
        // External system call to register collateral
        // In production: return $this->api->registerCollateral($value, $address);
        return sprintf('COL-%s-%s', date('YmdHis'), substr(md5($address . $value), 0, 8));
    }
}
