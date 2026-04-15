<?php

declare(strict_types=1);

namespace Be\Pattern\MedicalTriage\Reason;

/**
 * JTAS Protocol - Policy Reason (the key branching logic)
 *
 * Japan Triage and Acuity Scale assessment. This is the Policy Reason that
 * determines which Final path the patient takes, based on two signals that
 * every intake must capture:
 *
 * - Chief complaint (free-text)
 * - Consciousness level (JCS - Japan Coma Scale)
 *
 * A real ER protocol also considers vital signs; those are deliberately
 * omitted here to keep the demo focused on the branching pattern itself.
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
     * Assess patient using JTAS protocol.
     *
     * @return 'immediate'|'urgent'|'non-urgent'
     */
    public function assess(string $chiefComplaint, int $consciousnessLevel): string
    {
        // Level 1 (RED): Immediate - life-threatening
        if ($consciousnessLevel >= 100 || $this->matchesComplaint($chiefComplaint, self::IMMEDIATE_COMPLAINTS)) {
            return 'immediate';
        }

        // Level 2 (YELLOW): Urgent - potentially life-threatening
        if (($consciousnessLevel >= 1 && $consciousnessLevel <= 30)
            || $this->matchesComplaint($chiefComplaint, self::URGENT_COMPLAINTS)
        ) {
            return 'urgent';
        }

        // Level 3 (GREEN): Non-urgent - outpatient referral
        return 'non-urgent';
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
}
