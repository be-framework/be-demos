<?php

declare(strict_types=1);

namespace Be\Pattern\MedicalTriage\Tests;

use Be\Pattern\MedicalTriage\Exception\InvalidConsciousnessException;
use Be\Pattern\MedicalTriage\Exception\InvalidPatientIdException;
use Be\Pattern\MedicalTriage\Final\EmergencyAdmitted;
use Be\Pattern\MedicalTriage\Final\OutpatientReferred;
use Be\Pattern\MedicalTriage\Final\UrgentQueued;
use Be\Pattern\MedicalTriage\Input\PatientInput;
use Be\Pattern\MedicalTriage\Module\AppModule;
use Be\Pattern\MedicalTriage\Reason\ImmediateCase;
use Be\Pattern\MedicalTriage\Reason\JTASProtocol;
use Be\Pattern\MedicalTriage\Reason\NonUrgentCase;
use Be\Pattern\MedicalTriage\Reason\UrgentCase;
use Be\Pattern\MedicalTriage\Semantic\ConsciousnessLevel;
use Be\Pattern\MedicalTriage\Semantic\PatientId;
use Be\Framework\Becoming;
use Be\Framework\Exception\SemanticVariableException;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

class MedicalTriageTest extends TestCase
{
    // =========================================================================
    // End-to-end Becoming Flow Tests
    //
    // Drives a PatientInput through the full chain:
    //   PatientInput
    //     -> TriageLevelDetermined (sets $being discriminator)
    //     -> EmergencyAdmitted | UrgentQueued | OutpatientReferred
    // using $being type matching for branch selection, mirroring the
    // BeGreeting example from the Be Framework.
    // =========================================================================

    private function becoming(): Becoming
    {
        return new Becoming(
            new Injector(new AppModule()),
            'Be\\Pattern\\MedicalTriage\\Semantic',
        );
    }

    public function testBecomingFlowForEmergency(): void
    {
        $becoming = $this->becoming();

        $result = $becoming(new PatientInput(
            patientId: 'PT-10001',
            chiefComplaint: 'severe chest pain',
            consciousnessLevel: 0,
        ));

        $this->assertInstanceOf(EmergencyAdmitted::class, $result);
        $this->assertInstanceOf(ImmediateCase::class, $result->being);
        $this->assertSame('PT-10001', $result->patientId);
        $this->assertSame('RED', $result->triageCode);
        $this->assertSame('admitted', $result->status);
        $this->assertMatchesRegularExpression('/^ADM-\d{8}-[a-f0-9]{8}$/', $result->admissionId);
    }

    public function testBecomingFlowForEmergencyByConsciousness(): void
    {
        $becoming = $this->becoming();

        // JCS 200 (deep coma) -> immediate regardless of complaint
        $result = $becoming(new PatientInput(
            patientId: 'PT-10002',
            chiefComplaint: 'mild headache',
            consciousnessLevel: 200,
        ));

        $this->assertInstanceOf(EmergencyAdmitted::class, $result);
        $this->assertSame('RED', $result->triageCode);
    }

    public function testBecomingFlowForUrgent(): void
    {
        $becoming = $this->becoming();

        $result = $becoming(new PatientInput(
            patientId: 'PT-20001',
            chiefComplaint: 'difficulty breathing',
            consciousnessLevel: 0,
        ));

        $this->assertInstanceOf(UrgentQueued::class, $result);
        $this->assertInstanceOf(UrgentCase::class, $result->being);
        $this->assertSame('PT-20001', $result->patientId);
        $this->assertSame('YELLOW', $result->triageCode);
        $this->assertSame('queued', $result->status);
        $this->assertMatchesRegularExpression('/^QUE-\d{8}-\d{4}$/', $result->queueId);
        $this->assertGreaterThanOrEqual(1, $result->queuePosition);
        $this->assertLessThanOrEqual(5, $result->queuePosition);
    }

    public function testBecomingFlowForNonUrgent(): void
    {
        $becoming = $this->becoming();

        $result = $becoming(new PatientInput(
            patientId: 'PT-30001',
            chiefComplaint: 'mild headache',
            consciousnessLevel: 0,
        ));

        $this->assertInstanceOf(OutpatientReferred::class, $result);
        $this->assertInstanceOf(NonUrgentCase::class, $result->being);
        $this->assertSame('PT-30001', $result->patientId);
        $this->assertSame('GREEN', $result->triageCode);
        $this->assertSame('referred', $result->status);
        $this->assertMatchesRegularExpression('/^REF-\d{8}-[a-f0-9]{8}$/', $result->referralId);
    }

    public function testBecomingFlowRejectsInvalidPatientId(): void
    {
        $becoming = $this->becoming();

        $this->expectException(SemanticVariableException::class);

        $becoming(new PatientInput(
            patientId: 'INVALID-123', // PatientId semantic expects PT-\d+
            chiefComplaint: 'mild headache',
            consciousnessLevel: 0,
        ));
    }

    public function testBecomingFlowRejectsInvalidConsciousness(): void
    {
        $becoming = $this->becoming();

        $this->expectException(SemanticVariableException::class);

        $becoming(new PatientInput(
            patientId: 'PT-40001',
            chiefComplaint: 'mild headache',
            consciousnessLevel: 5, // 5 is not a valid JCS value
        ));
    }

    // =========================================================================
    // JTASProtocol Unit Tests (the policy that drives branching)
    // =========================================================================

    public function testJtasImmediateByComplaint(): void
    {
        $protocol = new JTASProtocol();
        $this->assertSame('immediate', $protocol->assess('severe chest pain', 0));
    }

    public function testJtasImmediateByConsciousness(): void
    {
        $protocol = new JTASProtocol();
        $this->assertSame('immediate', $protocol->assess('mild headache', 100));
    }

    public function testJtasUrgentByComplaint(): void
    {
        $protocol = new JTASProtocol();
        $this->assertSame('urgent', $protocol->assess('difficulty breathing', 0));
    }

    public function testJtasUrgentByConsciousness(): void
    {
        $protocol = new JTASProtocol();
        // JCS 10 = opens eyes on call -> urgent
        $this->assertSame('urgent', $protocol->assess('mild headache', 10));
    }

    public function testJtasNonUrgent(): void
    {
        $protocol = new JTASProtocol();
        $this->assertSame('non-urgent', $protocol->assess('mild cold symptoms', 0));
    }

    // =========================================================================
    // Semantic Validation Negative Tests
    // =========================================================================

    public function testInvalidPatientIdFormatThrowsException(): void
    {
        $this->expectException(InvalidPatientIdException::class);

        (new PatientId())->validate('INVALID-123');
    }

    public function testEmptyPatientIdThrowsException(): void
    {
        $this->expectException(InvalidPatientIdException::class);

        (new PatientId())->validate('');
    }

    public function testInvalidConsciousnessLevelThrowsException(): void
    {
        $this->expectException(InvalidConsciousnessException::class);

        (new ConsciousnessLevel())->validate(5); // 5 is not a valid JCS value
    }
}
