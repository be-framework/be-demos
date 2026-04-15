<?php

declare(strict_types=1);

namespace Be\Pattern\MedicalTriage\Reason;

/**
 * Non-Urgent Case - Reason strategy for outpatient-level triage (JTAS Level 3 / GREEN)
 *
 * Acts as a typed discriminator AND a behavior carrier: the Be Framework uses the
 * concrete type of {@see \Be\Pattern\MedicalTriage\Being\TriageLevelDetermined::$being}
 * to pick {@see \Be\Pattern\MedicalTriage\Final\OutpatientReferred}, and the chosen
 * Final delegates its domain work back to this object's {@see self::refer()}.
 */
final readonly class NonUrgentCase
{
    public string $triageCode;

    public function __construct()
    {
        $this->triageCode = 'GREEN';
    }

    /**
     * Create an outpatient referral for the patient.
     *
     * @return array{referralId: string, status: string}
     */
    public function refer(string $patientId): array
    {
        return [
            'referralId' => sprintf(
                'REF-%s-%s',
                date('Ymd'),
                substr(md5($patientId . $this->triageCode), 0, 8),
            ),
            'status' => 'referred',
        ];
    }
}
