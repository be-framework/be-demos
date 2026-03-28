# BE Framework Demos

A collection of demonstration projects showcasing the BE Framework's ontological programming approach.

## Philosophy

The BE Framework embodies the principle "Be, Don't Do" - modeling software as transformations of being rather than sequences of actions. Each demo illustrates different metamorphosis patterns through six philosophical layers:

| Layer | Greek/German | Role |
|-------|--------------|------|
| **Input** | δύναμις (Dynamis) | Raw potential entering the system |
| **Being** | Dasein | Existential state with computed properties |
| **Moment** | 契機 (Keiki) | Transitional phase with deferred Potentials |
| **Final** | ἐνέργεια (Energeia) | Fully actualized result |
| **Semantic** | Sinn | Domain validation rules |
| **Reason** | Sufficient Reason | Business logic and external integrations |

## Demo Catalog

### Beginner Level

#### [contact-form](./contact-form/)
**Pattern:** Linear Transformation
**Flow:** `Input → Being → Final`

The simplest BE Framework pattern. An email contact form demonstrating basic input validation, email normalization, and receipt generation.

```
ContactInput → EmailNormalized → ContactReceived
```

#### [user-registration](./user-registration/)
**Pattern:** Sequential Chain
**Flow:** `Input → Being(A) → Being(B) → Being(C) → Final`

User registration with chained Being transformations: email verification, password hashing, and profile enrichment.

```
RegistrationInput → EmailVerified → PasswordHashed → ProfileEnriched → UserRegistered
```

### Intermediate Level

#### [blog-publishing](./blog-publishing/)
**Pattern:** Diamond with Pure Data Moments
**Flow:** `Input → Being(A) + Being(B) → Moment(A) + Moment(B) → Final`

Article publishing demonstrating parallel Being paths that converge through pure data Moments (no Potentials).

```
ArticleInput ─┬→ MarkdownRendered → ContentPrepared ─┬→ ArticlePublished
              └→ SlugGenerated    → MetadataResolved ─┘
```

### Advanced Level

#### [medical-triage](./medical-triage/)
**Pattern:** Branching Metamorphosis
**Flow:** `Input → Being → [Branch] → Final(A) | Final(B) | Final(C)`

Emergency room triage implementing JTAS protocol. One input branches to three possible Finals based on severity assessment.

```
TriageInput → VitalsMeasured → TriageLevelDetermined
                                        │
              ┌─────────────────────────┼─────────────────────────┐
              ↓                         ↓                         ↓
        [immediate]               [urgent]                  [non-urgent]
              ↓                         ↓                         ↓
     EmergencyAdmitted           UrgentQueued           OutpatientReferred
```

#### [loan-application](./loan-application/)
**Pattern:** Cascade Diamond (2-Stage)
**Flow:** `Input → Stage1(parallel → converge) → Stage2(parallel → Final)`

Mortgage application with staged Moment realization. Stage 1 Moments realize at eligibility confirmation; Stage 2 Moments realize at final approval.

```
LoanInput → IdentityVerified ─┬→ CreditScored   → CreditApproved   ─┬→ EligibilityConfirmed
                              └→ IncomeAssessed → IncomeApproved   ─┘
                                                                     ↓
                              ┌→ PropertyAppraised → CollateralValued ─┬→ LoanApproved
                              └→ InsuranceQuoted   → InsurancePrepared ─┘
```

#### [insurance-claim](./insurance-claim/)
**Pattern:** Complex Convergence
**Flow:** `Input(A) + Input(B) → converge → parallel(3) → Branch → Final(A) | Final(B)`

Insurance claim processing with multiple input convergence, three-way parallel assessment, and branching finals.

```
ClaimInput ──┬→ ClaimRegistered ─┬→ ClaimValidated ─┬→ DamageAssessed  ─┬→ [threshold] → ClaimSettled
PolicyInput ─┴→ PolicyVerified  ─┘                  ├→ AdjusterAssigned ─┤              or
                                                    └→ FraudScreened   ─┘              ClaimEscalated
```

## Pattern Summary

| Pattern | Demo | Inputs | Beings | Moments | Finals |
|---------|------|--------|--------|---------|--------|
| Linear | contact-form | 1 | 1 | 0 | 1 |
| Sequential | user-registration | 1 | 3 | 0 | 1 |
| Diamond | blog-publishing | 1 | 2 | 2 | 1 |
| Branching | medical-triage | 1 | 2 | 2-3 | 3 |
| Cascade | loan-application | 1 | 5 | 4 | 1 |
| Complex | insurance-claim | 2 | 5 | 3 | 2 |

## Running Tests

Each demo includes comprehensive tests covering:
- Happy path integration tests
- Semantic validation unit tests
- Reason layer logic tests
- Potential idempotency tests (where applicable)

```bash
# Run all tests
composer test

# Run specific demo tests
./vendor/bin/phpunit demos/medical-triage/tests/
```

## Requirements

- PHP 8.2+
- [Ray.Di](https://ray-di.github.io/) (dependency injection)

## License

MIT

---

[日本語版はこちら](./README.ja.md)
