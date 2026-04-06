<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Reason;

/**
 * JTAS Protocol - Policy Reason (the key branching logic)
 *
 * Japan Triage and Acuity Scale assessment.
 * This is the Policy Reason that determines which Final path the patient takes.
 *
 * The assess() method returns triage level and code based on:
 * - Chief complaint severity
 * - Consciousness level (JCS - Japan Coma Scale)
 * - Vital sign abnormalities
 *
 * @link https://www.jaam.jp/html/info/info-20200327.htm
 */
final class JTASProtocol
{
    /** @var list<string> Complaints requiring immediate attention */
    private const IMMEDIATE_COMPLAINTS = [
        'chest pain',
        'cardiac arrest',
        'respiratory failure',
        'severe bleeding',
        'stroke',
        'anaphylaxis',
    ];

    /** @var list<string> Complaints requiring urgent attention */
    private const URGENT_COMPLAINTS = [
        'abdominal pain',
        'fracture',
        'high fever',
        'difficulty breathing',
        'severe headache',
        'seizure',
    ];

    /**
     * Assess patient using JTAS protocol
     *
     * @return array{level: string, code: string}
     */
    public function assess(
        string $chiefComplaint,
        int $consciousnessLevel,
        float $temperature,
        int $heartRate,
        int $bloodPressureSystolic
    ): array {
        // Level 1 (RED): Immediate - life-threatening
        if ($this->isImmediate($chiefComplaint, $consciousnessLevel, $temperature, $heartRate, $bloodPressureSystolic)) {
            return ['level' => 'immediate', 'code' => 'RED'];
        }

        // Level 2 (YELLOW): Urgent - potentially life-threatening
        if ($this->isUrgent($chiefComplaint, $consciousnessLevel, $temperature, $heartRate, $bloodPressureSystolic)) {
            return ['level' => 'urgent', 'code' => 'YELLOW'];
        }

        // Level 3 (GREEN): Non-urgent - outpatient referral
        return ['level' => 'non-urgent', 'code' => 'GREEN'];
    }

    private function isImmediate(
        string $chiefComplaint,
        int $consciousnessLevel,
        float $temperature,
        int $heartRate,
        int $bloodPressureSystolic
    ): bool {
        return $consciousnessLevel >= 100
            || $this->matchesComplaint($chiefComplaint, self::IMMEDIATE_COMPLAINTS)
            || $this->hasExtremeVitals($temperature, $heartRate, $bloodPressureSystolic);
    }

    private function isUrgent(
        string $chiefComplaint,
        int $consciousnessLevel,
        float $temperature,
        int $heartRate,
        int $bloodPressureSystolic
    ): bool {
        return ($consciousnessLevel >= 1 && $consciousnessLevel <= 30)
            || $this->matchesComplaint($chiefComplaint, self::URGENT_COMPLAINTS)
            || $this->hasAbnormalVitals($temperature, $heartRate, $bloodPressureSystolic);
    }

    /** @param list<string> $complaints */
    private function matchesComplaint(string $chiefComplaint, array $complaints): bool
    {
        $complaint = strtolower($chiefComplaint);

        foreach ($complaints as $keyword) {
            if (str_contains($complaint, $keyword)) {
                return true;
            }
        }

        return false;
    }

    private function hasExtremeVitals(float $temperature, int $heartRate, int $systolic): bool
    {
        return $temperature >= 41.0 || $temperature <= 32.0
            || $heartRate >= 150 || $heartRate <= 40
            || $systolic >= 220 || $systolic <= 70;
    }

    private function hasAbnormalVitals(float $temperature, int $heartRate, int $systolic): bool
    {
        return $temperature >= 39.0 || $temperature <= 35.0
            || $heartRate >= 120 || $heartRate <= 50
            || $systolic >= 180 || $systolic <= 80;
    }
}
