<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Tests;

use Be\Demo\MedicalTriage\Being\Path\ImmediatePath;
use Be\Demo\MedicalTriage\Being\Path\NonUrgentPath;
use Be\Demo\MedicalTriage\Being\Path\UrgentPath;
use Be\Demo\MedicalTriage\Being\TriageLevelDetermined;
use Be\Demo\MedicalTriage\Being\VitalsMeasured;
use Be\Demo\MedicalTriage\Exception\InvalidConsciousnessException;
use Be\Demo\MedicalTriage\Exception\InvalidHeartRateException;
use Be\Demo\MedicalTriage\Exception\InvalidPatientIdException;
use Be\Demo\MedicalTriage\Exception\InvalidTemperatureException;
use Be\Demo\MedicalTriage\Final\EmergencyAdmitted;
use Be\Demo\MedicalTriage\Final\OutpatientReferred;
use Be\Demo\MedicalTriage\Final\UrgentQueued;
use Be\Demo\MedicalTriage\Input\PatientInput;
use Be\Demo\MedicalTriage\Module\AppModule;
use Be\Demo\MedicalTriage\Moment\Potential\BedReservation;
use Be\Demo\MedicalTriage\Moment\Potential\TeamAlert;
use Be\Demo\MedicalTriage\Reason\BedAllocator;
use Be\Demo\MedicalTriage\Reason\JTASProtocol;
use Be\Demo\MedicalTriage\Reason\ReferralPolicy;
use Be\Demo\MedicalTriage\Reason\TeamDispatcher;
use Be\Demo\MedicalTriage\Reason\VitalsAssessor;
use Be\Demo\MedicalTriage\Semantic\ConsciousnessLevel;
use Be\Demo\MedicalTriage\Semantic\HeartRate;
use Be\Demo\MedicalTriage\Semantic\PatientId;
use Be\Demo\MedicalTriage\Semantic\Temperature;
use Be\Framework\Becoming;
use Be\Framework\Exception\SemanticVariableException;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

class MedicalTriageTest extends TestCase
{
    // =========================================================================
    // End-to-end Becoming Flow Tests
    //
    // These drive a PatientInput through the full chain:
    //   PatientInput
    //     -> VitalsMeasured
    //     -> TriageLevelDetermined (sets $being discriminator)
    //     -> EmergencyAdmitted | UrgentQueued | OutpatientReferred
    // using `$being` type matching for branch selection, mirroring the
    // BeGreeting example from the Be Framework.
    // =========================================================================

    private function becoming(): Becoming
    {
        return new Becoming(
            new Injector(new AppModule()),
            'Be\\Demo\\MedicalTriage\\Semantic',
        );
    }

    public function testBecomingFlowForEmergency(): void
    {
        $becoming = $this->becoming();

        // Severe chest pain + extreme fever -> JTASProtocol returns "immediate"
        $result = $becoming(new PatientInput(
            patientId: 'PT-10001',
            temperature: 41.5,
            heartRate: 160,
            bloodPressureSystolic: 60,
            bloodPressureDiastolic: 30,
            chiefComplaint: 'severe chest pain',
            consciousnessLevel: 0,
        ));

        $this->assertInstanceOf(EmergencyAdmitted::class, $result);
        $this->assertInstanceOf(ImmediatePath::class, $result->being);
        $this->assertSame('PT-10001', $result->patientId);
        $this->assertSame('RED', $result->triageCode);
        $this->assertSame('admitted', $result->status);
        $this->assertStringStartsWith('ADM-', $result->admissionId);
        $this->assertMatchesRegularExpression('/^ADM-\d{8}-[a-f0-9]{8}$/', $result->admissionId);

        // All Moments built and realized via self-completion
        $this->assertNotNull($result->bedAssigned->reservation->getBedNumber());
        $this->assertNotNull($result->teamAlerted->alert->getTeamId());
    }

    public function testBecomingFlowForUrgent(): void
    {
        $becoming = $this->becoming();

        // "difficulty breathing" complaint + stable vitals -> JTASProtocol returns "urgent"
        $result = $becoming(new PatientInput(
            patientId: 'PT-20001',
            temperature: 36.8,
            heartRate: 90,
            bloodPressureSystolic: 130,
            bloodPressureDiastolic: 85,
            chiefComplaint: 'difficulty breathing',
            consciousnessLevel: 0,
        ));

        $this->assertInstanceOf(UrgentQueued::class, $result);
        $this->assertInstanceOf(UrgentPath::class, $result->being);
        $this->assertSame('PT-20001', $result->patientId);
        $this->assertSame('urgent', $result->triageLevel);
        $this->assertSame('queued', $result->status);
        $this->assertStringStartsWith('QUE-', $result->queueId);
        $this->assertGreaterThanOrEqual(1, $result->queuePositioned->queuePosition);
        $this->assertLessThanOrEqual(5, $result->queuePositioned->queuePosition);
    }

    public function testBecomingFlowForNonUrgent(): void
    {
        $becoming = $this->becoming();

        // Mild headache + stable vitals + alert -> JTASProtocol returns "non-urgent"
        $result = $becoming(new PatientInput(
            patientId: 'PT-30001',
            temperature: 36.5,
            heartRate: 72,
            bloodPressureSystolic: 120,
            bloodPressureDiastolic: 80,
            chiefComplaint: 'mild headache',
            consciousnessLevel: 0,
        ));

        $this->assertInstanceOf(OutpatientReferred::class, $result);
        $this->assertInstanceOf(NonUrgentPath::class, $result->being);
        $this->assertSame('PT-30001', $result->patientId);
        $this->assertSame('referred', $result->status);
        $this->assertStringStartsWith('REF-', $result->referralNumber);
        $this->assertSame('Neurology', $result->referralCreated->department);
    }

    public function testBecomingFlowRejectsInvalidPatientId(): void
    {
        $becoming = $this->becoming();

        $this->expectException(SemanticVariableException::class);

        $becoming(new PatientInput(
            patientId: 'INVALID-123', // PatientId semantic expects PT-\d+
            temperature: 36.5,
            heartRate: 72,
            bloodPressureSystolic: 120,
            bloodPressureDiastolic: 80,
            chiefComplaint: 'mild headache',
            consciousnessLevel: 0,
        ));
    }

    public function testBecomingFlowRejectsOutOfRangeTemperature(): void
    {
        $becoming = $this->becoming();

        $this->expectException(SemanticVariableException::class);

        $becoming(new PatientInput(
            patientId: 'PT-40001',
            temperature: 50.0, // Temperature semantic rejects > 45.0
            heartRate: 72,
            bloodPressureSystolic: 120,
            bloodPressureDiastolic: 80,
            chiefComplaint: 'mild headache',
            consciousnessLevel: 0,
        ));
    }

    // =========================================================================
    // Being Unit Tests
    // =========================================================================

    public function testVitalsMeasuredCritical(): void
    {
        $assessor = new VitalsAssessor();
        $being = new VitalsMeasured(
            'PT-10001', 41.5, 160, 60, 30, 'severe chest pain', 0, $assessor,
        );

        $this->assertSame('critical', $being->vitalsSeverity);
    }

    public function testVitalsMeasuredModerate(): void
    {
        $assessor = new VitalsAssessor();
        $being = new VitalsMeasured(
            'PT-10002', 39.0, 105, 165, 95, 'fever', 0, $assessor,
        );

        $this->assertSame('moderate', $being->vitalsSeverity);
    }

    public function testVitalsMeasuredStable(): void
    {
        $assessor = new VitalsAssessor();
        $being = new VitalsMeasured(
            'PT-10003', 36.5, 72, 120, 80, 'mild headache', 0, $assessor,
        );

        $this->assertSame('stable', $being->vitalsSeverity);
    }

    public function testTriageLevelImmediate(): void
    {
        $protocol = new JTASProtocol();
        $being = new TriageLevelDetermined(
            'PT-10001', 36.5, 72, 120, 80, 'chest pain', 0, 'stable', $protocol,
        );

        $this->assertSame('immediate', $being->triageLevel);
        $this->assertSame('RED', $being->triageCode);
        $this->assertInstanceOf(ImmediatePath::class, $being->being);
    }

    public function testTriageLevelUrgent(): void
    {
        $protocol = new JTASProtocol();
        $being = new TriageLevelDetermined(
            'PT-20001', 36.5, 72, 120, 80, 'abdominal pain', 0, 'stable', $protocol,
        );

        $this->assertSame('urgent', $being->triageLevel);
        $this->assertSame('YELLOW', $being->triageCode);
        $this->assertInstanceOf(UrgentPath::class, $being->being);
    }

    public function testTriageLevelNonUrgent(): void
    {
        $protocol = new JTASProtocol();
        $being = new TriageLevelDetermined(
            'PT-30001', 36.5, 72, 120, 80, 'mild headache', 0, 'stable', $protocol,
        );

        $this->assertSame('non-urgent', $being->triageLevel);
        $this->assertSame('GREEN', $being->triageCode);
        $this->assertInstanceOf(NonUrgentPath::class, $being->being);
    }

    public function testTriageLevelImmediateByConsciousness(): void
    {
        $protocol = new JTASProtocol();
        // JCS 200 = deep coma -> always immediate
        $being = new TriageLevelDetermined(
            'PT-40001', 36.5, 72, 120, 80, 'headache', 200, 'stable', $protocol,
        );

        $this->assertSame('immediate', $being->triageLevel);
        $this->assertSame('RED', $being->triageCode);
    }

    public function testTriageLevelUrgentByConsciousness(): void
    {
        $protocol = new JTASProtocol();
        // JCS 10 = opens eyes on call -> urgent
        $being = new TriageLevelDetermined(
            'PT-40002', 36.5, 72, 120, 80, 'mild headache', 10, 'stable', $protocol,
        );

        $this->assertSame('urgent', $being->triageLevel);
        $this->assertSame('YELLOW', $being->triageCode);
    }

    // =========================================================================
    // Emergency Path Tests (immediate -> EmergencyAdmitted)
    // =========================================================================

    public function testEmergencyAdmitted(): void
    {
        $final = new EmergencyAdmitted(
            new ImmediatePath(),
            'PT-10001',
            'RED',
            new BedAllocator(),
            new TeamDispatcher(),
        );

        $this->assertStringStartsWith('ADM-', $final->admissionId);
        $this->assertSame('admitted', $final->status);
        $this->assertSame('PT-10001', $final->patientId);
        $this->assertSame('RED', $final->triageCode);
    }

    public function testEmergencyAdmittedRealizesAllMoments(): void
    {
        $final = new EmergencyAdmitted(
            new ImmediatePath(),
            'PT-10002',
            'RED',
            new BedAllocator(),
            new TeamDispatcher(),
        );

        // After Final: Moments were built and their Potentials realized during self-completion
        $this->assertNotNull($final->bedAssigned->reservation->getBedNumber());
        $this->assertNotNull($final->teamAlerted->alert->getTeamId());
    }

    public function testEmergencyAdmissionIdFormat(): void
    {
        $final = new EmergencyAdmitted(
            new ImmediatePath(),
            'PT-10003',
            'RED',
            new BedAllocator(),
            new TeamDispatcher(),
        );

        // Admission ID format: ADM-YYYYMMDD-xxxxxxxx
        $this->assertMatchesRegularExpression('/^ADM-\d{8}-[a-f0-9]{8}$/', $final->admissionId);
    }

    // =========================================================================
    // Urgent Path Tests (urgent -> UrgentQueued)
    // =========================================================================

    public function testUrgentQueued(): void
    {
        $final = new UrgentQueued(new UrgentPath(), 'PT-20001', 'urgent');

        $this->assertStringStartsWith('QUE-', $final->queueId);
        $this->assertSame('queued', $final->status);
        $this->assertSame('PT-20001', $final->patientId);
    }

    public function testUrgentQueuedPosition(): void
    {
        $final = new UrgentQueued(new UrgentPath(), 'PT-20002', 'urgent');

        $this->assertGreaterThanOrEqual(1, $final->queuePositioned->queuePosition);
        $this->assertLessThanOrEqual(5, $final->queuePositioned->queuePosition);
        $this->assertSame(
            $final->queuePositioned->queuePosition * 15,
            $final->queuePositioned->estimatedWaitMinutes,
        );
    }

    // =========================================================================
    // Non-urgent Path Tests (non-urgent -> OutpatientReferred)
    // =========================================================================

    public function testOutpatientReferred(): void
    {
        $final = new OutpatientReferred(
            new NonUrgentPath(),
            'PT-30001',
            'mild headache',
            new ReferralPolicy(),
        );

        $this->assertStringStartsWith('REF-', $final->referralNumber);
        $this->assertSame('referred', $final->status);
        $this->assertSame('PT-30001', $final->patientId);
    }

    public function testOutpatientReferredDepartment(): void
    {
        $final = new OutpatientReferred(
            new NonUrgentPath(),
            'PT-30002',
            'mild headache',
            new ReferralPolicy(),
        );

        $this->assertSame('Neurology', $final->referralCreated->department);
    }

    public function testOutpatientReferredDefaultDepartment(): void
    {
        $final = new OutpatientReferred(
            new NonUrgentPath(),
            'PT-30003',
            'general checkup',
            new ReferralPolicy(),
        );

        $this->assertSame('General Internal Medicine', $final->referralCreated->department);
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
        $reservation = new BedReservation(
            'BED-RSV-TEST',
            function () use (&$callCount) {
                $callCount++;
                return 'ER-BED-001';
            },
        );

        $reservation->be();
        $reservation->be(); // Second call should be no-op

        $this->assertSame(1, $callCount);
        $this->assertSame('ER-BED-001', $reservation->getBedNumber());
    }

    public function testTeamAlertIdempotent(): void
    {
        $callCount = 0;
        $alert = new TeamAlert(
            'ALERT-TEST',
            function () use (&$callCount) {
                $callCount++;
                return 'TEAM-TRAUMA-001';
            },
        );

        $alert->be();
        $alert->be(); // Second call should be no-op

        $this->assertSame(1, $callCount);
        $this->assertSame('TEAM-TRAUMA-001', $alert->getTeamId());
    }

    // =========================================================================
    // Semantic Validation Negative Tests
    // =========================================================================

    public function testInvalidPatientIdFormatThrowsException(): void
    {
        $this->expectException(InvalidPatientIdException::class);

        $validator = new PatientId();
        $validator->validate('INVALID-123');
    }

    public function testEmptyPatientIdThrowsException(): void
    {
        $this->expectException(InvalidPatientIdException::class);

        $validator = new PatientId();
        $validator->validate('');
    }

    public function testTemperatureTooLowThrowsException(): void
    {
        $this->expectException(InvalidTemperatureException::class);

        $validator = new Temperature();
        $validator->validate(29.9);
    }

    public function testTemperatureTooHighThrowsException(): void
    {
        $this->expectException(InvalidTemperatureException::class);

        $validator = new Temperature();
        $validator->validate(45.1);
    }

    public function testHeartRateNegativeThrowsException(): void
    {
        $this->expectException(InvalidHeartRateException::class);

        $validator = new HeartRate();
        $validator->validate(-1);
    }

    public function testHeartRateTooHighThrowsException(): void
    {
        $this->expectException(InvalidHeartRateException::class);

        $validator = new HeartRate();
        $validator->validate(301);
    }

    public function testInvalidConsciousnessLevelThrowsException(): void
    {
        $this->expectException(InvalidConsciousnessException::class);

        $validator = new ConsciousnessLevel();
        $validator->validate(5); // 5 is not a valid JCS value
    }
}
