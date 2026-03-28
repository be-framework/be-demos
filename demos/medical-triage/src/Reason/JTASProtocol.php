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
        // Altered consciousness (JCS >= 100) is always immediate
        if ($consciousnessLevel >= 100) {
            return true;
        }

        // Known immediate complaints
        $complaint = strtolower($chiefComplaint);
        foreach (self::IMMEDIATE_COMPLAINTS as $immediate) {
            if (str_contains($complaint, $immediate)) {
                return true;
            }
        }

        // Extreme vital signs
        if ($temperature >= 41.0 || $temperature <= 32.0) {
            return true;
        }
        if ($heartRate >= 150 || $heartRate <= 40) {
            return true;
        }
        if ($bloodPressureSystolic >= 220 || $bloodPressureSystolic <= 70) {
            return true;
        }

        return false;
    }

    private function isUrgent(
        string $chiefComplaint,
        int $consciousnessLevel,
        float $temperature,
        int $heartRate,
        int $bloodPressureSystolic
    ): bool {
        // Mild consciousness disturbance (JCS 1-30) is urgent
        if ($consciousnessLevel >= 1 && $consciousnessLevel <= 30) {
            return true;
        }

        // Known urgent complaints
        $complaint = strtolower($chiefComplaint);
        foreach (self::URGENT_COMPLAINTS as $urgent) {
            if (str_contains($complaint, $urgent)) {
                return true;
            }
        }

        // Moderately abnormal vital signs
        if ($temperature >= 39.0 || $temperature <= 35.0) {
            return true;
        }
        if ($heartRate >= 120 || $heartRate <= 50) {
            return true;
        }
        if ($bloodPressureSystolic >= 180 || $bloodPressureSystolic <= 80) {
            return true;
        }

        return false;
    }
}
