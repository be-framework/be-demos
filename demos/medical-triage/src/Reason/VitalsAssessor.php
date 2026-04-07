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
        if ($this->hasCriticalVitals($temperature, $heartRate, $bloodPressureSystolic, $bloodPressureDiastolic)) {
            return 'critical';
        }

        // Moderate: any vital sign outside normal range
        if ($this->hasModerateVitals($temperature, $heartRate, $bloodPressureSystolic, $bloodPressureDiastolic)) {
            return 'moderate';
        }

        return 'stable';
    }

    private function hasCriticalVitals(float $temperature, int $heartRate, int $systolic, int $diastolic): bool
    {
        return !$this->inRange($temperature, 32.0, 40.0)
            || !$this->inRange($heartRate, 40, 150)
            || !$this->inRange($systolic, 70, 220)
            || !$this->inRange($diastolic, 40, 130);
    }

    private function hasModerateVitals(float $temperature, int $heartRate, int $systolic, int $diastolic): bool
    {
        return !$this->inRange($temperature, 35.0, 38.5)
            || !$this->inRange($heartRate, 50, 100)
            || !$this->inRange($systolic, 90, 160)
            || !$this->inRange($diastolic, 50, 100);
    }

    private function inRange(float|int $value, float|int $min, float|int $max): bool
    {
        return $value > $min && $value < $max;
    }
}
