# Insurance Claim Demo - BE Framework

**Level:** Expert
**Concepts:** Multiple Input Convergence, Complex Metamorphosis, 3-Way Diamond, Branching Finals

## Overview

This demo demonstrates the most advanced BE Framework patterns: **multiple Input convergence**,
a **3-way parallel diamond**, and **branching to different Finals** based on domain logic.

Two separate Inputs (ClaimInput and PolicyInput) independently enter the system, converge into
a validated state, fan out into three parallel processing branches, and then converge again
into one of two possible Finals depending on a settlement threshold.

## Flow

```
ClaimInput + PolicyInput (two Inputs)
    → ClaimRegistered(Being) + PolicyVerified(Being)
    → ClaimValidated(Being)                           ← convergence of two inputs
    → [DamageAssessed + AdjusterAssigned + FraudScreened]  ← 3-way parallel (Being)
    → [DamageValued + AdjustmentReviewed + FraudCleared]   ← Moments
    ├── amount <= threshold → ClaimSettled(Final)      ← with PaymentDispatched (Moment+Potential)
    └── amount > threshold  → ClaimEscalated(Final)    ← with EscalationQueued (Moment, no Potential)
```

## Key Concepts Demonstrated

### 1. Multiple Input Convergence

Unlike simpler demos with a single Input, this demo shows two independent Inputs
(`ClaimInput` and `PolicyInput`) that each carry their own `#[Be]` attribute pointing
to the same set of possible Finals. The framework converges them at `ClaimValidated`,
which draws fields from both `ClaimRegistered` (born from ClaimInput) and
`PolicyVerified` (born from PolicyInput).

### 2. 3-Way Parallel Diamond

After convergence, three Beings are created in parallel:
- **DamageAssessed** - appraises the physical damage
- **AdjusterAssigned** - allocates a human adjuster
- **FraudScreened** - screens for fraud indicators

Each produces a corresponding Moment that feeds into the Final.

### 3. Branching Finals

The metamorphosis branches into two possible Finals:
- **ClaimSettled** - when the assessed amount is within auto-approval threshold.
  Includes `PaymentDispatched` (a Moment with `PaymentExecution` Potential).
- **ClaimEscalated** - when the amount exceeds the threshold.
  Includes `EscalationQueued` (pure data Moment, no Potential to realize).

### 4. Moments With and Without Potential

- `DamageValued` and `PaymentDispatched` implement `MomentInterface` and carry
  Potentials (`DamageValuation`, `PaymentExecution`) that are realized via `be()`.
- `AdjustmentReviewed`, `FraudCleared`, and `EscalationQueued` are pure data
  Moments with no Potential - they record outcomes but have nothing to realize.

### 5. Idempotent Potentials

Both `DamageValuation` and `PaymentExecution` are idempotent - calling `be()`
multiple times only executes the side effect once, matching the pattern from
the order-processing demo.

## Layer Architecture

| Layer      | Classes |
|------------|---------|
| **Input**  | `ClaimInput`, `PolicyInput` |
| **Being**  | `ClaimRegistered`, `PolicyVerified`, `ClaimValidated`, `DamageAssessed`, `AdjusterAssigned`, `FraudScreened` |
| **Moment** | `DamageValued`, `AdjustmentReviewed`, `FraudCleared`, `PaymentDispatched`, `EscalationQueued` |
| **Potential** | `DamageValuation`, `PaymentExecution` |
| **Final**  | `ClaimSettled`, `ClaimEscalated` |
| **Semantic** | `PolicyNumber`, `IncidentDate`, `IncidentType`, `ClaimAmount`, `CoverageType`, `ClaimantId` |
| **Reason** | `ClaimRegistrar`, `PolicyRegistry`, `CoverageValidator`, `DamageAppraiser`, `AdjusterAllocator`, `FraudDetector`, `PaymentProcessor`, `SettlementPolicy` |
| **Exception** | `InvalidPolicyNumberException`, `InvalidIncidentDateException`, `InvalidIncidentTypeException`, `InvalidClaimAmountException`, `InvalidCoverageTypeException`, `InvalidClaimantIdException` |

## Running Tests

```bash
composer install
./vendor/bin/phpunit
```
