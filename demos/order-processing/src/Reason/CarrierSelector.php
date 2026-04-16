<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Reason;

/**
 * Carrier Selector - Reason for carrier selection
 */
final class CarrierSelector
{
    /** @return array{id: string, name: string} */
    public function select(string $postalCode): array
    {
        // Demo: select carrier based on region
        $region = substr($postalCode, 0, 1);

        return match ($region) {
            '1', '2', '3' => ['id' => 'YAMATO', 'name' => 'Yamato Transport'],
            '4', '5', '6' => ['id' => 'SAGAWA', 'name' => 'Sagawa Express'],
            default => ['id' => 'JPPOST', 'name' => 'Japan Post'],
        };
    }
}
