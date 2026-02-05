<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Reason;

/**
 * Vitals Assessor - Reason (stateless assessor)
 *
 * Assesses overall vital sign severity based on individual measurements.
 */
final class VitalsAssessor
{
    /**
     * Assess overall severity from vital signs
     *
     * @return string 'critical'|'moderate'|'stable'
     */
    public function assess(
        float $temperature,
        int $heartRate,
        int $bloodPressureSystolic,
        int $bloodPressureDiastolic
    ): string {
        // Critical: any vital sign in danger zone
        if ($temperature >= 40.0 || $temperature <= 32.0) {
            return 'critical';
        }
        if ($heartRate >= 150 || $heartRate <= 40) {
            return 'critical';
        }
        if ($bloodPressureSystolic >= 220 || $bloodPressureSystolic <= 70) {
            return 'critical';
        }
        if ($bloodPressureDiastolic >= 130 || $bloodPressureDiastolic <= 40) {
            return 'critical';
        }

        // Moderate: any vital sign outside normal range
        if ($temperature >= 38.5 || $temperature <= 35.0) {
            return 'moderate';
        }
        if ($heartRate >= 100 || $heartRate <= 50) {
            return 'moderate';
        }
        if ($bloodPressureSystolic >= 160 || $bloodPressureSystolic <= 90) {
            return 'moderate';
        }
        if ($bloodPressureDiastolic >= 100 || $bloodPressureDiastolic <= 50) {
            return 'moderate';
        }

        return 'stable';
    }
}
