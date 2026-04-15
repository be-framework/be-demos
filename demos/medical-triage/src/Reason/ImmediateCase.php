<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Reason;

/**
 * Immediate Case - Reason strategy for life-threatening triage (JTAS Level 1 / RED)
 *
 * Acts as a typed discriminator AND a behavior carrier: the Be Framework uses the
 * concrete type of {@see \Be\Demo\MedicalTriage\Being\TriageLevelDetermined::$being}
 * to pick {@see \Be\Demo\MedicalTriage\Final\EmergencyAdmitted}, and the chosen
 * Final delegates its domain work back to this object's {@see self::admit()}.
 *
 * This mirrors the canonical FormalStyle/CasualStyle pattern from the
 * Be Framework's BeGreeting example.
 */
final readonly class ImmediateCase
{
    public string $triageCode;

    public function __construct()
    {
        $this->triageCode = 'RED';
    }

    /**
     * Admit the patient to the emergency department.
     *
     * @return array{admissionId: string, status: string}
     */
    public function admit(string $patientId): array
    {
        return [
            'admissionId' => sprintf(
                'ADM-%s-%s',
                date('Ymd'),
                substr(md5($patientId . $this->triageCode), 0, 8),
            ),
            'status' => 'admitted',
        ];
    }
}
