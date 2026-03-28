<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Reason;

/**
 * Referral Policy - Reason (stateless policy)
 *
 * Determines which outpatient department a patient should be referred to
 * based on their chief complaint.
 */
final class ReferralPolicy
{
    /** @var array<string, string> Complaint keywords to department mappings */
    private const DEPARTMENT_MAP = [
        'headache' => 'Neurology',
        'back pain' => 'Orthopedics',
        'joint pain' => 'Orthopedics',
        'skin rash' => 'Dermatology',
        'sore throat' => 'ENT',
        'ear pain' => 'ENT',
        'eye' => 'Ophthalmology',
        'stomach' => 'Gastroenterology',
        'cough' => 'Pulmonology',
        'allergy' => 'Allergy',
        'anxiety' => 'Psychiatry',
        'depression' => 'Psychiatry',
        'diabetes' => 'Endocrinology',
        'urinary' => 'Urology',
    ];

    /**
     * Determine the appropriate outpatient department
     */
    public function determineDepartment(string $chiefComplaint): string
    {
        $complaint = strtolower($chiefComplaint);

        foreach (self::DEPARTMENT_MAP as $keyword => $department) {
            if (str_contains($complaint, $keyword)) {
                return $department;
            }
        }

        // Default: General Internal Medicine
        return 'General Internal Medicine';
    }
}
