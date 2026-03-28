<?php

declare(strict_types=1);

namespace Be\Demo\InsuranceClaim\Reason;

/**
 * Claim Registrar - Reason for claim registration
 *
 * Generates unique claim numbers for incoming claims.
 */
final class ClaimRegistrar
{
    public function register(string $claimantId, string $incidentDate, string $incidentType): string
    {
        return sprintf(
            'CLN-%s-%s-%s',
            strtoupper(substr($incidentType, 0, 3)),
            date('Ymd'),
            substr(md5($claimantId . $incidentDate . $incidentType), 0, 6)
        );
    }
}
