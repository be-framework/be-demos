<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Reason;

/**
 * Urgent Case - Reason strategy for potentially life-threatening triage (JTAS Level 2 / YELLOW)
 *
 * Acts as a typed discriminator AND a behavior carrier: the Be Framework uses the
 * concrete type of {@see \Be\Demo\MedicalTriage\Being\TriageLevelDetermined::$being}
 * to pick {@see \Be\Demo\MedicalTriage\Final\UrgentQueued}, and the chosen
 * Final delegates its domain work back to this object's {@see self::queue()}.
 */
final readonly class UrgentCase
{
    public string $triageCode;

    public function __construct()
    {
        $this->triageCode = 'YELLOW';
    }

    /**
     * Place the patient in the urgent-care queue.
     *
     * @return array{queueId: string, queuePosition: int, status: string}
     */
    public function queue(string $patientId): array
    {
        $position = abs(crc32($patientId)) % 5 + 1;

        return [
            'queueId' => sprintf('QUE-%s-%04d', date('Ymd'), $position),
            'queuePosition' => $position,
            'status' => 'queued',
        ];
    }
}
