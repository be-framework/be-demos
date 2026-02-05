<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Tests;

use Be\Demo\MedicalTriage\Being\TriageLevelDetermined;
use Be\Demo\MedicalTriage\Being\VitalsMeasured;
use Be\Demo\MedicalTriage\Final\EmergencyAdmitted;
use Be\Demo\MedicalTriage\Final\OutpatientReferred;
use Be\Demo\MedicalTriage\Final\UrgentQueued;
use Be\Demo\MedicalTriage\Moment\BedAssigned;
use Be\Demo\MedicalTriage\Moment\QueuePositioned;
use Be\Demo\MedicalTriage\Moment\ReferralCreated;
use Be\Demo\MedicalTriage\Moment\TeamAlerted;
use Be\Demo\MedicalTriage\Reason\BedAllocator;
use Be\Demo\MedicalTriage\Reason\JTASProtocol;
use Be\Demo\MedicalTriage\Reason\ReferralPolicy;
use Be\Demo\MedicalTriage\Reason\TeamDispatcher;
use Be\Demo\MedicalTriage\Reason\VitalsAssessor;
use PHPUnit\Framework\TestCase;

class MedicalTriageTest extends TestCase
{
    // =========================================================================
    // Being Tests
    // =========================================================================

    public function testVitalsMeasuredCritical(): void
    {
        $assessor = new VitalsAssessor();
        $being = new VitalsMeasured(41.5, 160, 60, 30, $assessor);

        $this->assertSame('critical', $being->vitalsSeverity);
    }

    public function testVitalsMeasuredModerate(): void
    {
        $assessor = new VitalsAssessor();
        $being = new VitalsMeasured(39.0, 105, 165, 95, $assessor);

        $this->assertSame('moderate', $being->vitalsSeverity);
    }

    public function testVitalsMeasuredStable(): void
    {
        $assessor = new VitalsAssessor();
        $being = new VitalsMeasured(36.5, 72, 120, 80, $assessor);

        $this->assertSame('stable', $being->vitalsSeverity);
    }

    public function testTriageLevelImmediate(): void
    {
        $protocol = new JTASProtocol();
        $being = new TriageLevelDetermined('chest pain', 0, 36.5, 72, 120, $protocol);

        $this->assertSame('immediate', $being->triageLevel);
        $this->assertSame('RED', $being->triageCode);
    }

    public function testTriageLevelUrgent(): void
    {
        $protocol = new JTASProtocol();
        $being = new TriageLevelDetermined('abdominal pain', 0, 36.5, 72, 120, $protocol);

        $this->assertSame('urgent', $being->triageLevel);
        $this->assertSame('YELLOW', $being->triageCode);
    }

    public function testTriageLevelNonUrgent(): void
    {
        $protocol = new JTASProtocol();
        $being = new TriageLevelDetermined('mild headache', 0, 36.5, 72, 120, $protocol);

        $this->assertSame('non-urgent', $being->triageLevel);
        $this->assertSame('GREEN', $being->triageCode);
    }

    public function testTriageLevelImmediateByConsciousness(): void
    {
        $protocol = new JTASProtocol();
        // JCS 200 = deep coma -> always immediate
        $being = new TriageLevelDetermined('headache', 200, 36.5, 72, 120, $protocol);

        $this->assertSame('immediate', $being->triageLevel);
        $this->assertSame('RED', $being->triageCode);
    }

    public function testTriageLevelUrgentByConsciousness(): void
    {
        $protocol = new JTASProtocol();
        // JCS 10 = opens eyes on call -> urgent
        $being = new TriageLevelDetermined('mild headache', 10, 36.5, 72, 120, $protocol);

        $this->assertSame('urgent', $being->triageLevel);
        $this->assertSame('YELLOW', $being->triageCode);
    }

    // =========================================================================
    // Emergency Path Tests (immediate -> EmergencyAdmitted)
    // =========================================================================

    public function testEmergencyAdmitted(): void
    {
        $bedAllocator = new BedAllocator();
        $teamDispatcher = new TeamDispatcher();

        $bedAssigned = new BedAssigned('PT-10001', $bedAllocator);
        $teamAlerted = new TeamAlerted('RED', $teamDispatcher);

        $final = new EmergencyAdmitted($bedAssigned, $teamAlerted);

        $this->assertStringStartsWith('ADM-', $final->admissionId);
        $this->assertSame('admitted', $final->status);
        $this->assertSame($bedAssigned, $final->bedAssigned);
        $this->assertSame($teamAlerted, $final->teamAlerted);
    }

    public function testEmergencyAdmittedRealizesAllMoments(): void
    {
        $bedAllocator = new BedAllocator();
        $teamDispatcher = new TeamDispatcher();

        $bedAssigned = new BedAssigned('PT-10002', $bedAllocator);
        $teamAlerted = new TeamAlerted('RED', $teamDispatcher);

        // Before Final: potentials not yet realized
        $this->assertNull($bedAssigned->reservation->getBedNumber());
        $this->assertNull($teamAlerted->alert->getTeamId());

        $final = new EmergencyAdmitted($bedAssigned, $teamAlerted);

        // After Final: potentials realized
        $this->assertNotNull($bedAssigned->reservation->getBedNumber());
        $this->assertNotNull($teamAlerted->alert->getTeamId());
    }

    public function testEmergencyAdmissionIdFormat(): void
    {
        $bedAllocator = new BedAllocator();
        $teamDispatcher = new TeamDispatcher();

        $bedAssigned = new BedAssigned('PT-10003', $bedAllocator);
        $teamAlerted = new TeamAlerted('RED', $teamDispatcher);

        $final = new EmergencyAdmitted($bedAssigned, $teamAlerted);

        // Admission ID format: ADM-YYYYMMDD-xxxxxxxx
        $this->assertMatchesRegularExpression('/^ADM-\d{8}-[a-f0-9]{8}$/', $final->admissionId);
    }

    // =========================================================================
    // Urgent Path Tests (urgent -> UrgentQueued)
    // =========================================================================

    public function testUrgentQueued(): void
    {
        $queuePositioned = new QueuePositioned('PT-20001', 'urgent');

        $final = new UrgentQueued($queuePositioned);

        $this->assertStringStartsWith('QUE-', $final->queueId);
        $this->assertSame('queued', $final->status);
        $this->assertSame($queuePositioned, $final->queuePositioned);
    }

    public function testUrgentQueuedPosition(): void
    {
        $queuePositioned = new QueuePositioned('PT-20002', 'urgent');

        $this->assertGreaterThanOrEqual(1, $queuePositioned->queuePosition);
        $this->assertLessThanOrEqual(5, $queuePositioned->queuePosition);
        $this->assertSame($queuePositioned->queuePosition * 15, $queuePositioned->estimatedWaitMinutes);
    }

    // =========================================================================
    // Non-urgent Path Tests (non-urgent -> OutpatientReferred)
    // =========================================================================

    public function testOutpatientReferred(): void
    {
        $referralPolicy = new ReferralPolicy();
        $referralCreated = new ReferralCreated('PT-30001', 'mild headache', $referralPolicy);

        $final = new OutpatientReferred($referralCreated);

        $this->assertStringStartsWith('REF-', $final->referralNumber);
        $this->assertSame('referred', $final->status);
        $this->assertSame($referralCreated, $final->referralCreated);
    }

    public function testOutpatientReferredDepartment(): void
    {
        $referralPolicy = new ReferralPolicy();
        $referralCreated = new ReferralCreated('PT-30002', 'mild headache', $referralPolicy);

        $this->assertSame('Neurology', $referralCreated->department);
    }

    public function testOutpatientReferredDefaultDepartment(): void
    {
        $referralPolicy = new ReferralPolicy();
        $referralCreated = new ReferralCreated('PT-30003', 'general checkup', $referralPolicy);

        $this->assertSame('General Internal Medicine', $referralCreated->department);
    }

    // =========================================================================
    // Reason Tests
    // =========================================================================

    public function testJTASProtocolImmediateByComplaint(): void
    {
        $protocol = new JTASProtocol();
        $result = $protocol->assess('severe chest pain', 0, 36.5, 72, 120);

        $this->assertSame('immediate', $result['level']);
        $this->assertSame('RED', $result['code']);
    }

    public function testJTASProtocolUrgentByComplaint(): void
    {
        $protocol = new JTASProtocol();
        $result = $protocol->assess('difficulty breathing', 0, 36.5, 72, 120);

        $this->assertSame('urgent', $result['level']);
        $this->assertSame('YELLOW', $result['code']);
    }

    public function testJTASProtocolNonUrgent(): void
    {
        $protocol = new JTASProtocol();
        $result = $protocol->assess('mild cold symptoms', 0, 36.5, 72, 120);

        $this->assertSame('non-urgent', $result['level']);
        $this->assertSame('GREEN', $result['code']);
    }

    public function testJTASProtocolImmediateByExtremeVitals(): void
    {
        $protocol = new JTASProtocol();
        // Extreme temperature triggers immediate
        $result = $protocol->assess('mild cold', 0, 41.5, 72, 120);

        $this->assertSame('immediate', $result['level']);
        $this->assertSame('RED', $result['code']);
    }

    public function testReferralPolicyDepartments(): void
    {
        $policy = new ReferralPolicy();

        $this->assertSame('Neurology', $policy->determineDepartment('headache'));
        $this->assertSame('Orthopedics', $policy->determineDepartment('back pain'));
        $this->assertSame('Dermatology', $policy->determineDepartment('skin rash on arms'));
        $this->assertSame('ENT', $policy->determineDepartment('sore throat'));
        $this->assertSame('General Internal Medicine', $policy->determineDepartment('general checkup'));
    }

    public function testVitalsAssessorLevels(): void
    {
        $assessor = new VitalsAssessor();

        // Critical: extreme heart rate
        $this->assertSame('critical', $assessor->assess(36.5, 160, 120, 80));

        // Moderate: elevated temperature
        $this->assertSame('moderate', $assessor->assess(39.0, 72, 120, 80));

        // Stable: all normal
        $this->assertSame('stable', $assessor->assess(36.5, 72, 120, 80));
    }

    // =========================================================================
    // Potential Idempotency Tests
    // =========================================================================

    public function testBedReservationIdempotent(): void
    {
        $callCount = 0;
        $reservation = new \Be\Demo\MedicalTriage\Moment\Potential\BedReservation(
            'BED-RSV-TEST',
            function () use (&$callCount) {
                $callCount++;
                return 'ER-BED-001';
            }
        );

        $reservation->be();
        $reservation->be(); // Second call should be no-op

        $this->assertSame(1, $callCount);
        $this->assertSame('ER-BED-001', $reservation->getBedNumber());
    }

    public function testTeamAlertIdempotent(): void
    {
        $callCount = 0;
        $alert = new \Be\Demo\MedicalTriage\Moment\Potential\TeamAlert(
            'ALERT-TEST',
            function () use (&$callCount) {
                $callCount++;
                return 'TEAM-TRAUMA-001';
            }
        );

        $alert->be();
        $alert->be(); // Second call should be no-op

        $this->assertSame(1, $callCount);
        $this->assertSame('TEAM-TRAUMA-001', $alert->getTeamId());
    }
}
