<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Tests;

use Be\Pattern\InsuranceClaim\Being\AdjusterAssigned;
use Be\Pattern\InsuranceClaim\Being\ClaimRegistered;
use Be\Pattern\InsuranceClaim\Being\ClaimValidated;
use Be\Pattern\InsuranceClaim\Being\DamageAssessed;
use Be\Pattern\InsuranceClaim\Being\FraudScreened;
use Be\Pattern\InsuranceClaim\Being\PolicyVerified;
use Be\Pattern\InsuranceClaim\Final\ClaimEscalated;
use Be\Pattern\InsuranceClaim\Final\ClaimSettled;
use Be\Pattern\InsuranceClaim\Moment\AdjustmentReviewed;
use Be\Pattern\InsuranceClaim\Moment\DamageValued;
use Be\Pattern\InsuranceClaim\Moment\EscalationQueued;
use Be\Pattern\InsuranceClaim\Moment\FraudCleared;
use Be\Pattern\InsuranceClaim\Moment\PaymentDispatched;
use Be\Pattern\InsuranceClaim\Moment\Potential\DamageValuation;
use Be\Pattern\InsuranceClaim\Moment\Potential\PaymentExecution;
use Be\Pattern\InsuranceClaim\Reason\AdjusterAllocator;
use Be\Pattern\InsuranceClaim\Reason\AdjusterAllocatorInterface;
use Be\Pattern\InsuranceClaim\Reason\ClaimRegistrar;
use Be\Pattern\InsuranceClaim\Reason\CoverageValidator;
use Be\Pattern\InsuranceClaim\Reason\DamageAppraiser;
use Be\Pattern\InsuranceClaim\Reason\DamageAppraiserInterface;
use Be\Pattern\InsuranceClaim\Reason\FraudDetector;
use Be\Pattern\InsuranceClaim\Reason\FraudDetectorInterface;
use Be\Pattern\InsuranceClaim\Reason\PaymentProcessor;
use Be\Pattern\InsuranceClaim\Reason\PaymentProcessorInterface;
use Be\Pattern\InsuranceClaim\Reason\PolicyRegistry;
use Be\Pattern\InsuranceClaim\Reason\PolicyRegistryInterface;
use Be\Pattern\InsuranceClaim\Reason\SettlementPolicy;
use Be\Pattern\InsuranceClaim\Semantic\ClaimAmount;
use Be\Pattern\InsuranceClaim\Semantic\ClaimantId;
use Be\Pattern\InsuranceClaim\Semantic\CoverageType;
use Be\Pattern\InsuranceClaim\Semantic\IncidentDate;
use Be\Pattern\InsuranceClaim\Semantic\IncidentType;
use Be\Pattern\InsuranceClaim\Semantic\PolicyNumber;
use Be\Pattern\InsuranceClaim\Exception\InvalidPolicyNumberException;
use Be\Pattern\InsuranceClaim\Exception\InvalidIncidentDateException;
use Be\Pattern\InsuranceClaim\Exception\InvalidIncidentTypeException;
use Be\Pattern\InsuranceClaim\Exception\InvalidClaimAmountException;
use Be\Pattern\InsuranceClaim\Exception\InvalidCoverageTypeException;
use Be\Pattern\InsuranceClaim\Exception\InvalidClaimantIdException;
use PHPUnit\Framework\TestCase;

class InsuranceClaimTest extends TestCase
{
    // ──────────────────────────────────────────────
    // Semantic Validation Tests
    // ──────────────────────────────────────────────

    public function testValidPolicyNumber(): void
    {
        $semantic = new PolicyNumber();
        $semantic->validate('PLY-123456');
        $this->addToAssertionCount(1);
    }

    public function testInvalidPolicyNumber(): void
    {
        $this->expectException(InvalidPolicyNumberException::class);
        (new PolicyNumber())->validate('INVALID-123');
    }

    public function testValidIncidentDate(): void
    {
        $semantic = new IncidentDate();
        $semantic->validate('2024-01-15');
        $this->addToAssertionCount(1);
    }

    public function testInvalidIncidentDateFormat(): void
    {
        $this->expectException(InvalidIncidentDateException::class);
        (new IncidentDate())->validate('15/01/2024');
    }

    public function testFutureIncidentDate(): void
    {
        $this->expectException(InvalidIncidentDateException::class);
        (new IncidentDate())->validate('2099-12-31');
    }

    public function testValidIncidentType(): void
    {
        $semantic = new IncidentType();
        $semantic->validate('accident');
        $this->addToAssertionCount(1);
    }

    public function testInvalidIncidentType(): void
    {
        $this->expectException(InvalidIncidentTypeException::class);
        (new IncidentType())->validate('earthquake');
    }

    public function testValidClaimAmount(): void
    {
        $semantic = new ClaimAmount();
        $semantic->validate(500_000);
        $this->addToAssertionCount(1);
    }

    public function testInvalidClaimAmountZero(): void
    {
        $this->expectException(InvalidClaimAmountException::class);
        (new ClaimAmount())->validate(0);
    }

    public function testInvalidClaimAmountExceedsMax(): void
    {
        $this->expectException(InvalidClaimAmountException::class);
        (new ClaimAmount())->validate(100_000_001);
    }

    public function testValidCoverageType(): void
    {
        $semantic = new CoverageType();
        $semantic->validate('comprehensive');
        $this->addToAssertionCount(1);
    }

    public function testInvalidCoverageType(): void
    {
        $this->expectException(InvalidCoverageTypeException::class);
        (new CoverageType())->validate('umbrella');
    }

    public function testValidClaimantId(): void
    {
        $semantic = new ClaimantId();
        $semantic->validate('CLM-001');
        $this->addToAssertionCount(1);
    }

    public function testInvalidClaimantId(): void
    {
        $this->expectException(InvalidClaimantIdException::class);
        (new ClaimantId())->validate('INVALID');
    }

    // ──────────────────────────────────────────────
    // Being Tests
    // ──────────────────────────────────────────────

    public function testClaimRegistered(): void
    {
        $registrar = new ClaimRegistrar();
        $being = new ClaimRegistered(
            claimantId: 'CLM-001',
            incidentDate: '2024-06-15',
            incidentType: 'accident',
            registrar: $registrar
        );

        $this->assertStringStartsWith('CLN-ACC-', $being->claimNumber);
        $this->assertSame('CLM-001', $being->claimantId);
        $this->assertSame('accident', $being->incidentType);
    }

    public function testPolicyVerified(): void
    {
        $registry = new PolicyRegistry();
        $being = new PolicyVerified(
            policyNumber: 'PLY-123456',
            policyHolderId: 'PH-001',
            registry: $registry
        );

        $this->assertSame('active', $being->policyStatus);
        $this->assertNotEmpty($being->expiryDate);
    }

    public function testClaimValidated(): void
    {
        $validator = new CoverageValidator();
        $being = new ClaimValidated(
            claimNumber: 'CLN-ACC-20240615-abc123',
            policyNumber: 'PLY-123456',
            incidentType: 'accident',
            coverageType: 'comprehensive',
            validator: $validator
        );

        $this->assertTrue($being->coverageApplicable);
        $this->assertStringStartsWith('VAL-', $being->validationId);
    }

    public function testClaimValidatedNotApplicable(): void
    {
        $validator = new CoverageValidator();
        $being = new ClaimValidated(
            claimNumber: 'CLN-MED-20240615-abc123',
            policyNumber: 'PLY-123456',
            incidentType: 'medical',
            coverageType: 'property',
            validator: $validator
        );

        $this->assertFalse($being->coverageApplicable);
    }

    public function testDamageAssessed(): void
    {
        $appraiser = new DamageAppraiser();
        $being = new DamageAssessed(
            incidentType: 'accident',
            estimatedAmount: 500_000,
            description: 'Vehicle collision at intersection',
            appraiser: $appraiser
        );

        $this->assertSame(450_000, $being->assessedAmount); // 500000 * 0.90
        $this->assertSame('moderate', $being->damageGrade);
    }

    public function testAdjusterAssigned(): void
    {
        $allocator = new AdjusterAllocator();
        $being = new AdjusterAssigned(
            claimNumber: 'CLN-ACC-20240615-abc123',
            incidentType: 'accident',
            allocator: $allocator
        );

        $this->assertStringStartsWith('ADJ-ACC-', $being->adjusterId);
        $this->assertNotEmpty($being->assignedAt);
    }

    public function testFraudScreened(): void
    {
        $detector = new FraudDetector();
        $being = new FraudScreened(
            claimantId: 'CLM-001',
            estimatedAmount: 500_000,
            description: 'Vehicle collision',
            detector: $detector
        );

        $this->assertGreaterThanOrEqual(0.0, $being->riskScore);
        $this->assertLessThanOrEqual(1.0, $being->riskScore);
        $this->assertIsBool($being->flagged);
    }

    // ──────────────────────────────────────────────
    // Reason Tests
    // ──────────────────────────────────────────────

    public function testSettlementPolicyAutoApprovable(): void
    {
        $policy = new SettlementPolicy();
        $this->assertTrue($policy->isAutoApprovable(500_000));
        $this->assertTrue($policy->isAutoApprovable(1_000_000));
        $this->assertFalse($policy->isAutoApprovable(1_000_001));
    }

    public function testCoverageValidatorComprehensive(): void
    {
        $validator = new CoverageValidator();

        foreach (['accident', 'theft', 'fire', 'natural_disaster', 'medical'] as $type) {
            $result = $validator->validate($type, 'comprehensive');
            $this->assertTrue($result['applicable'], "Expected $type to be covered by comprehensive");
        }
    }

    public function testCoverageValidatorLiability(): void
    {
        $validator = new CoverageValidator();

        $result = $validator->validate('accident', 'liability');
        $this->assertTrue($result['applicable']);

        $result = $validator->validate('theft', 'liability');
        $this->assertFalse($result['applicable']);
    }

    // ──────────────────────────────────────────────
    // Potential Tests
    // ──────────────────────────────────────────────

    public function testDamageValuationIdempotent(): void
    {
        $callCount = 0;
        $valuation = new DamageValuation(
            500_000,
            function () use (&$callCount): string {
                $callCount++;
                return 'DVAL-TEST-001';
            }
        );

        $this->assertNull($valuation->getValuationId());
        $valuation->be();
        $this->assertSame('DVAL-TEST-001', $valuation->getValuationId());
        $valuation->be(); // idempotent
        $this->assertSame(1, $callCount);
    }

    public function testPaymentExecutionIdempotent(): void
    {
        $callCount = 0;
        $execution = new PaymentExecution(
            'AUTH-TEST-001',
            500_000,
            function () use (&$callCount): string {
                $callCount++;
                return 'TXN-TEST-001';
            }
        );

        $this->assertNull($execution->getTransactionId());
        $execution->be();
        $this->assertSame('TXN-TEST-001', $execution->getTransactionId());
        $execution->be(); // idempotent
        $this->assertSame(1, $callCount);
    }

    // ──────────────────────────────────────────────
    // Final Settlement Path Tests
    // ──────────────────────────────────────────────

    public function testClaimSettledPath(): void
    {
        // Simulate the settled path: amount <= threshold
        $damageValued = new DamageValued(
            appraiser: $this->createDamageAppraiserForSettlement(450_000),
        );

        $adjustmentReviewed = new AdjustmentReviewed(
            allocator: $this->createAdjusterAllocatorMock(),
        );

        $fraudCleared = new FraudCleared(
            detector: $this->createFraudDetectorMock(0.1),
        );

        $paymentDispatched = new PaymentDispatched(
            processor: $this->createPaymentProcessorMock(450_000),
        );

        $settled = new ClaimSettled(
            damageValued: $damageValued,
            adjustmentReviewed: $adjustmentReviewed,
            fraudCleared: $fraudCleared,
            paymentDispatched: $paymentDispatched,
        );

        $this->assertStringStartsWith('STL-', $settled->settlementId);
        $this->assertSame(450_000, $settled->paidAmount);
        $this->assertNotEmpty($settled->settledAt);

        // Verify Moments are realized
        $this->assertNotNull($settled->damageValued->valuation->getValuationId());
        $this->assertNotNull($settled->paymentDispatched->execution->getTransactionId());
    }

    // ──────────────────────────────────────────────
    // Final Escalation Path Tests
    // ──────────────────────────────────────────────

    public function testClaimEscalatedPath(): void
    {
        // Simulate the escalated path: amount > threshold
        $damageValued = new DamageValued(
            appraiser: $this->createDamageAppraiserForSettlement(2_000_000),
        );

        $adjustmentReviewed = new AdjustmentReviewed(
            allocator: $this->createAdjusterAllocatorMock(),
        );

        $fraudCleared = new FraudCleared(
            detector: $this->createFraudDetectorMock(0.3),
        );

        $escalationQueued = new EscalationQueued(
            escalationId: 'ESQ-20240615-0001',
            priority: 'high',
        );

        $escalated = new ClaimEscalated(
            damageValued: $damageValued,
            adjustmentReviewed: $adjustmentReviewed,
            fraudCleared: $fraudCleared,
            escalationQueued: $escalationQueued,
        );

        $this->assertStringStartsWith('ESC-', $escalated->escalationNumber);
        $this->assertStringContainsString('exceeds auto-approval threshold', $escalated->reason);
        $this->assertNotEmpty($escalated->escalatedAt);

        // Verify DamageValued is realized but no payment
        $this->assertNotNull($escalated->damageValued->valuation->getValuationId());
    }

    // ──────────────────────────────────────────────
    // Helper Methods
    // ──────────────────────────────────────────────

    private function createDamageAppraiserForSettlement(int $amount): DamageAppraiserInterface
    {
        return new class($amount) implements DamageAppraiserInterface {
            public function __construct(private readonly int $amount)
            {
            }

            public function appraise(string $incidentType, int $estimatedAmount, string $description): array
            {
                return [
                    'assessedAmount' => $this->amount,
                    'damageGrade' => 'moderate',
                ];
            }

            public function value(): DamageValuation
            {
                return new DamageValuation(
                    $this->amount,
                    fn () => 'DVAL-TEST-' . $this->amount,
                );
            }
        };
    }

    private function createAdjusterAllocatorMock(): AdjusterAllocatorInterface
    {
        return new class implements AdjusterAllocatorInterface {
            public function allocate(string $claimNumber, string $incidentType): array
            {
                return [
                    'adjusterId' => 'ADJ-TEST-001',
                    'assignedAt' => '2024-06-15T10:00:00+00:00',
                ];
            }

            public function review(): array
            {
                return [
                    'adjusterId' => 'ADJ-TEST-001',
                    'reviewNotes' => 'Test review completed.',
                ];
            }
        };
    }

    private function createFraudDetectorMock(float $riskScore): FraudDetectorInterface
    {
        return new class($riskScore) implements FraudDetectorInterface {
            public function __construct(private readonly float $riskScore)
            {
            }

            public function screen(string $claimantId, int $estimatedAmount, string $description): array
            {
                return [
                    'riskScore' => $this->riskScore,
                    'flagged' => $this->riskScore > 0.7,
                ];
            }

            public function clear(): array
            {
                return [
                    'clearanceId' => 'FRC-TEST-001',
                    'riskScore' => $this->riskScore,
                ];
            }
        };
    }

    private function createPaymentProcessorMock(int $amount): PaymentProcessorInterface
    {
        return new class($amount) implements PaymentProcessorInterface {
            public function __construct(private readonly int $amount)
            {
            }

            public function authorize(): PaymentExecution
            {
                return new PaymentExecution(
                    'AUTH-TEST-' . $this->amount,
                    $this->amount,
                    fn () => 'TXN-TEST-' . $this->amount,
                );
            }
        };
    }
}
