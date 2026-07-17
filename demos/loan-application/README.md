# Loan Application - Cascade Metamorphosis Demo

**Level:** Advanced
**Pattern:** Two-stage diamond, staged Moment realization

## Overview

This demo demonstrates **cascade metamorphosis** -- two diamond patterns chained in sequence. Each diamond stage has its own parallel branches and convergence point, with Moments realized at their respective convergence boundaries.

## Flow

```
LoanInput
    │
    ▼
IdentityVerified (Being)
    │
    ├──────────────────────────┐
    ▼                          ▼
CreditScored (Being)    IncomeAssessed (Being)        ← Stage 1 parallel
    │                          │
    ▼                          ▼
CreditApproved (Moment)  IncomeApproved (Moment)      ← Stage 1 Moments
    │                          │
    └──────────┬───────────────┘
               ▼
    EligibilityConfirmed (Being)                       ← Stage 1 convergence
               │
    ┌──────────┴───────────────┐
    ▼                          ▼
PropertyAppraised (Being) InsuranceQuoted (Being)      ← Stage 2 parallel
    │                          │
    ▼                          ▼
CollateralValued (Moment) InsurancePrepared (Moment)   ← Stage 2 Moments
    │                          │
    └──────────┬───────────────┘
               ▼
        LoanApproved (Final)                           ← Stage 2 convergence
```

## Key Concepts

### Cascade Metamorphosis

Unlike a single diamond where all Moments converge at one Final point, cascade metamorphosis chains multiple diamonds. Each convergence point becomes the entry to the next diamond stage.

### Staged Moment Realization

- **Stage 1 Moments** (`CreditApproved`, `IncomeApproved`) are realized at `EligibilityConfirmed`
- **Stage 2 Moments** (`CollateralValued`, `InsurancePrepared`) are realized at `LoanApproved` (Final)

Each stage's Moments are realized only at their respective convergence boundary, not before. This ensures that side effects (credit inquiry finalization, collateral registration, insurance binding) occur at the correct semantic boundary.

### Moment Types

- **CreditApproved** - Implements `MomentInterface`. Has `CreditInquiry` Potential. Calling `be()` finalizes the credit bureau inquiry.
- **IncomeApproved** - Pure data Moment (no `MomentInterface`). Carries income assessment result without side effects.
- **CollateralValued** - Implements `MomentInterface`. Has `CollateralRegistration` Potential. Calling `be()` registers the property as collateral.
- **InsurancePrepared** - Implements `MomentInterface`. Has `InsuranceContract` Potential. Calling `be()` binds the insurance policy.

### Idempotent Potentials

All Potential classes (`CreditInquiry`, `CollateralRegistration`, `InsuranceContract`) guarantee idempotent `be()` calls. Once realized, subsequent calls are no-ops.

## Architecture Layers

| Layer | Purpose | Files |
|-------|---------|-------|
| **Input** | Entry point with `#[Be]` attribute | `LoanInput` |
| **Being** | Domain state transitions | `IdentityVerified`, `CreditScored`, `IncomeAssessed`, `EligibilityConfirmed`, `PropertyAppraised`, `InsuranceQuoted` |
| **Moment** | Parts with potential | `CreditApproved`, `IncomeApproved`, `CollateralValued`, `InsurancePrepared` |
| **Potential** | Deferred side effects | `CreditInquiry`, `CollateralRegistration`, `InsuranceContract` |
| **Final** | Terminal convergence | `LoanApproved` |
| **Semantic** | Input validation | `ApplicantId`, `AnnualIncome`, `EmploymentYears`, `RequestedAmount`, `PropertyAddress` |
| **Reason** | Domain logic & gateways | `IdentityVerifier`, `CreditBureau`, `IncomePolicy`, `PropertyAppraisal`, `InsuranceQuoter`, `LoanPolicy` |
| **Exception** | Domain exceptions with i18n | 5 exception classes with en/ja messages |

## Running Tests

```bash
composer install
./vendor/bin/phpunit tests/
```
