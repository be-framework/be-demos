<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Tests;

use Be\Pattern\LoanApplication\Being\CreditScored;
use Be\Pattern\LoanApplication\Being\EligibilityConfirmed;
use Be\Pattern\LoanApplication\Being\IdentityVerified;
use Be\Pattern\LoanApplication\Being\IncomeAssessed;
use Be\Pattern\LoanApplication\Being\InsuranceQuoted;
use Be\Pattern\LoanApplication\Being\PropertyAppraised;
use Be\Pattern\LoanApplication\Exception\InvalidApplicantIdException;
use Be\Pattern\LoanApplication\Exception\InvalidIncomeException;
use Be\Pattern\LoanApplication\Exception\InvalidLoanAmountException;
use Be\Pattern\LoanApplication\Final\LoanApproved;
use Be\Pattern\LoanApplication\Moment\CollateralValued;
use Be\Pattern\LoanApplication\Moment\CreditApproved;
use Be\Pattern\LoanApplication\Moment\IncomeApproved;
use Be\Pattern\LoanApplication\Moment\InsurancePrepared;
use Be\Pattern\LoanApplication\Moment\Potential\CollateralRegistration;
use Be\Pattern\LoanApplication\Moment\Potential\CreditInquiry;
use Be\Pattern\LoanApplication\Moment\Potential\InsuranceContract;
use Be\Pattern\LoanApplication\Reason\CreditBureau;
use Be\Pattern\LoanApplication\Reason\IdentityVerifier;
use Be\Pattern\LoanApplication\Reason\IncomePolicy;
use Be\Pattern\LoanApplication\Reason\InsuranceQuoter;
use Be\Pattern\LoanApplication\Reason\LoanPolicy;
use Be\Pattern\LoanApplication\Reason\PropertyAppraisal;
use Be\Pattern\LoanApplication\Semantic\AnnualIncome;
use Be\Pattern\LoanApplication\Semantic\ApplicantId;
use Be\Pattern\LoanApplication\Semantic\RequestedAmount;
use Be\Pattern\LoanApplication\Semantic\PropertyAddress;
use Be\Pattern\LoanApplication\Semantic\EmploymentYears;
use Be\Pattern\LoanApplication\Exception\InvalidPropertyAddressException;
use Be\Pattern\LoanApplication\Exception\InvalidEmploymentException;
use PHPUnit\Framework\TestCase;

class LoanApplicationTest extends TestCase
{
    // ─── Semantic Layer Tests ────────────────────────────────────────

    public function testApplicantIdValidFormat(): void
    {
        $validator = new ApplicantId();
        $validator->validate('APP-001');
        $this->addToAssertionCount(1); // No exception = pass
    }

    public function testApplicantIdInvalidFormat(): void
    {
        $this->expectException(InvalidApplicantIdException::class);
        $validator = new ApplicantId();
        $validator->validate('INVALID');
    }

    public function testAnnualIncomePositive(): void
    {
        $validator = new AnnualIncome();
        $validator->validate(5000000);
        $this->addToAssertionCount(1);
    }

    public function testAnnualIncomeZeroThrows(): void
    {
        $this->expectException(InvalidIncomeException::class);
        $validator = new AnnualIncome();
        $validator->validate(0);
    }

    public function testLoanAmountWithinRange(): void
    {
        $validator = new RequestedAmount();
        $validator->validate(50000000);
        $this->addToAssertionCount(1);
    }

    public function testLoanAmountTooLow(): void
    {
        $this->expectException(InvalidLoanAmountException::class);
        $validator = new RequestedAmount();
        $validator->validate(100);
    }

    public function testLoanAmountTooHigh(): void
    {
        $this->expectException(InvalidLoanAmountException::class);
        $validator = new RequestedAmount();
        $validator->validate(999999999);
    }

    // ─── Reason Layer Tests ─────────────────────────────────────────

    public function testIdentityVerifier(): void
    {
        $verifier = new IdentityVerifier();
        $id = $verifier->verify('APP-001');
        $this->assertStringStartsWith('VER-', $id);
    }

    public function testCreditBureauScore(): void
    {
        $bureau = new CreditBureau();
        $result = $bureau->score('APP-001');
        $this->assertArrayHasKey('score', $result);
        $this->assertArrayHasKey('rating', $result);
        $this->assertGreaterThanOrEqual(600, $result['score']);
        $this->assertLessThanOrEqual(799, $result['score']);
        $this->assertContains($result['rating'], ['A', 'B', 'C', 'D']);
    }

    public function testCreditBureauInquiry(): void
    {
        $bureau = new CreditBureau();
        $bureau->score('APP-001');
        $inquiry = $bureau->inquire();
        $this->assertInstanceOf(CreditInquiry::class, $inquiry);
        $this->assertNull($inquiry->getInquiryId()); // Not yet realized
        $inquiry->be();
        $this->assertNotNull($inquiry->getInquiryId()); // Now realized
        $this->assertStringStartsWith('INQ-', $inquiry->getInquiryId());
    }

    public function testIncomePolicyDti(): void
    {
        $policy = new IncomePolicy();
        $dti = $policy->calculateDti(6000000, 50000000);
        $this->assertGreaterThan(0.0, $dti);
        $this->assertLessThan(1.0, $dti);
    }

    public function testIncomePolicyPreservesZeroDti(): void
    {
        // Huge income + minimal loan legitimately rounds to a DTI of 0.0;
        // the fallback must not overwrite a computed value
        $policy = new IncomePolicy();
        $policy->calculateDti(700000000, 1000000);
        $this->assertSame(0.0, $policy->getAssessmentResult()['dti']);
    }

    public function testIncomePolicyStability(): void
    {
        $policy = new IncomePolicy();
        $this->assertSame('excellent', $policy->assessStability(15));
        $this->assertSame('good', $policy->assessStability(7));
        $this->assertSame('fair', $policy->assessStability(3));
        $this->assertSame('poor', $policy->assessStability(1));
    }

    public function testPropertyAppraisal(): void
    {
        $appraiser = new PropertyAppraisal();
        $value = $appraiser->appraise('Tokyo Shibuya 1-1-1', 'house');
        $this->assertGreaterThan(0, $value);
    }

    public function testPropertyAppraisalRegistration(): void
    {
        $appraiser = new PropertyAppraisal();
        $appraiser->appraise('Tokyo Shibuya 1-1-1', 'apartment');
        $registration = $appraiser->register();
        $this->assertInstanceOf(CollateralRegistration::class, $registration);
        $this->assertNull($registration->getRegistrationId());
        $registration->be();
        $this->assertNotNull($registration->getRegistrationId());
        $this->assertStringStartsWith('COL-', $registration->getRegistrationId());
    }

    public function testInsuranceQuoter(): void
    {
        $quoter = new InsuranceQuoter();
        $result = $quoter->quote('APP-001', 50000000);
        $this->assertArrayHasKey('monthlyPremium', $result);
        $this->assertArrayHasKey('coverageAmount', $result);
        $this->assertSame(150000, $result['monthlyPremium']); // 0.3% of 50M
        $this->assertSame(50000000, $result['coverageAmount']);
    }

    public function testInsuranceQuoterPrepare(): void
    {
        $quoter = new InsuranceQuoter();
        $quoter->quote('APP-001', 50000000);
        $contract = $quoter->prepare();
        $this->assertInstanceOf(InsuranceContract::class, $contract);
        $this->assertNull($contract->getPolicyId());
        $contract->be();
        $this->assertNotNull($contract->getPolicyId());
        $this->assertStringStartsWith('INS-', $contract->getPolicyId());
    }

    public function testLoanPolicyApprovedAmount(): void
    {
        $policy = new LoanPolicy();
        // Requested 50M, appraised at 60M -> max LTV 80% = 48M
        $approved = $policy->calculateApprovedAmount(50000000, 60000000);
        $this->assertSame(48000000, $approved);

        // Requested 30M, appraised at 60M -> max LTV 80% = 48M, but requested only 30M
        $approved = $policy->calculateApprovedAmount(30000000, 60000000);
        $this->assertSame(30000000, $approved);
    }

    public function testLoanPolicyInterestRate(): void
    {
        $policy = new LoanPolicy();
        // Low LTV (50%) = base rate
        $rate = $policy->calculateInterestRate(25000000, 50000000);
        $this->assertSame(1.5, $rate);

        // High LTV (80%) = base rate + 0.75
        $rate = $policy->calculateInterestRate(40000000, 50000000);
        $this->assertSame(2.25, $rate);
    }

    // ─── Potential Idempotency Tests ────────────────────────────────

    public function testCreditInquiryIdempotent(): void
    {
        $inquiry = new CreditInquiry(720, 'B', fn () => 'INQ-TEST-001');
        $inquiry->be();
        $firstId = $inquiry->getInquiryId();
        $inquiry->be(); // Should not change
        $this->assertSame($firstId, $inquiry->getInquiryId());
    }

    public function testCollateralRegistrationIdempotent(): void
    {
        $registration = new CollateralRegistration(50000000, 'Tokyo', fn () => 'COL-TEST-001');
        $registration->be();
        $firstId = $registration->getRegistrationId();
        $registration->be(); // Should not change
        $this->assertSame($firstId, $registration->getRegistrationId());
    }

    public function testInsuranceContractIdempotent(): void
    {
        $contract = new InsuranceContract(15000, 50000000, fn () => 'INS-TEST-001');
        $contract->be();
        $firstId = $contract->getPolicyId();
        $contract->be(); // Should not change
        $this->assertSame($firstId, $contract->getPolicyId());
    }

    // ─── Cascade Metamorphosis Integration Test ─────────────────────

    public function testStage1Convergence(): void
    {
        // Stage 1: CreditApproved + IncomeApproved -> EligibilityConfirmed
        $bureau = new CreditBureau();
        $bureau->score('APP-001');
        $creditApproved = new CreditApproved($bureau);

        $policy = new IncomePolicy();
        $policy->calculateDti(6000000, 50000000);
        $policy->assessStability(10);
        $incomeApproved = new IncomeApproved($policy);

        $eligibility = new EligibilityConfirmed($creditApproved, $incomeApproved);

        $this->assertStringStartsWith('ELIG-', $eligibility->eligibilityId);
        // CreditApproved should be realized after convergence
        $this->assertNotNull($eligibility->creditApproved->inquiry->getInquiryId());
    }

    public function testStage2ConvergenceToFinal(): void
    {
        // Stage 2: CollateralValued + InsurancePrepared -> LoanApproved
        $appraiser = new PropertyAppraisal();
        $appraiser->appraise('Tokyo Shibuya 1-1-1', 'house');
        $collateral = new CollateralValued($appraiser);

        $quoter = new InsuranceQuoter();
        $quoter->quote('APP-001', 50000000);
        $insurance = new InsurancePrepared($quoter);

        $loanPolicy = new LoanPolicy();

        $final = new LoanApproved(
            eligibilityId: 'ELIG-20260101-abc12345',
            requestedAmount: 50000000,
            collateral: $collateral,
            insurance: $insurance,
            policy: $loanPolicy,
        );

        $this->assertStringStartsWith('LOAN-', $final->loanId);
        $this->assertGreaterThan(0, $final->approvedAmount);
        $this->assertGreaterThan(0.0, $final->interestRate);
        $this->assertNotEmpty($final->approvedAt);

        // Verify Stage 2 Moments were realized
        $this->assertNotNull($final->collateral->registration->getRegistrationId());
        $this->assertNotNull($final->insurance->contract->getPolicyId());
    }

    public function testFullCascadeMetamorphosis(): void
    {
        // ─── Stage 1: Identity -> Parallel -> Convergence ───
        $verifier = new IdentityVerifier();
        $identityVerified = new IdentityVerified('APP-001', $verifier);
        $this->assertStringStartsWith('VER-', $identityVerified->verificationId);

        // Parallel: Credit + Income
        $bureau = new CreditBureau();
        $creditScored = new CreditScored('APP-001', $bureau);
        $this->assertGreaterThanOrEqual(600, $creditScored->creditScore);

        $incomePolicy = new IncomePolicy();
        $incomeAssessed = new IncomeAssessed(6000000, 10, 50000000, $incomePolicy);
        $this->assertGreaterThan(0.0, $incomeAssessed->debtToIncomeRatio);

        // Stage 1 Moments
        $creditApproved = new CreditApproved($bureau);
        $incomeApproved = new IncomeApproved($incomePolicy);

        // Stage 1 Convergence
        $eligibility = new EligibilityConfirmed($creditApproved, $incomeApproved);
        $this->assertStringStartsWith('ELIG-', $eligibility->eligibilityId);

        // ─── Stage 2: Parallel -> Final Convergence ─────────
        $appraiser = new PropertyAppraisal();
        $propertyAppraised = new PropertyAppraised('Tokyo Shibuya 1-1-1', 'house', $appraiser);
        $this->assertGreaterThan(0, $propertyAppraised->appraisedValue);

        $quoter = new InsuranceQuoter();
        $insuranceQuoted = new InsuranceQuoted('APP-001', 50000000, $quoter);
        $this->assertGreaterThan(0, $insuranceQuoted->monthlyPremium);

        // Stage 2 Moments
        $collateral = new CollateralValued($appraiser);
        $insurance = new InsurancePrepared($quoter);

        // Stage 2 Convergence = Final
        $loanPolicy = new LoanPolicy();
        $final = new LoanApproved(
            eligibilityId: $eligibility->eligibilityId,
            requestedAmount: 50000000,
            collateral: $collateral,
            insurance: $insurance,
            policy: $loanPolicy,
        );

        // Verify final state
        $this->assertStringStartsWith('LOAN-', $final->loanId);
        $this->assertGreaterThan(0, $final->approvedAmount);
        $this->assertLessThanOrEqual(50000000, $final->approvedAmount);
        $this->assertGreaterThanOrEqual(1.5, $final->interestRate);
        $this->assertNotEmpty($final->approvedAt);

        // Verify all Stage 2 potentials are realized
        $this->assertStringStartsWith('COL-', $final->collateral->registration->getRegistrationId());
        $this->assertStringStartsWith('INS-', $final->insurance->contract->getPolicyId());
    }

    // ─── Additional Semantic Negative Tests ─────────────────────────

    public function testNegativeIncomeThrows(): void
    {
        $this->expectException(InvalidIncomeException::class);
        $validator = new AnnualIncome();
        $validator->validate(-1000000);
    }

    public function testEmptyPropertyAddressThrows(): void
    {
        $this->expectException(InvalidPropertyAddressException::class);
        $validator = new PropertyAddress();
        $validator->validate('');
    }

    public function testPropertyAddressTooShortThrows(): void
    {
        $this->expectException(InvalidPropertyAddressException::class);
        $validator = new PropertyAddress();
        $validator->validate('ABC');
    }

    public function testPropertyAddressTooLongThrows(): void
    {
        $this->expectException(InvalidPropertyAddressException::class);
        $validator = new PropertyAddress();
        $validator->validate(str_repeat('a', 201));
    }

    public function testNegativeEmploymentYearsThrows(): void
    {
        $this->expectException(InvalidEmploymentException::class);
        $validator = new EmploymentYears();
        $validator->validate(-1);
    }

    public function testEmploymentYearsTooHighThrows(): void
    {
        $this->expectException(InvalidEmploymentException::class);
        $validator = new EmploymentYears();
        $validator->validate(51);
    }
}
