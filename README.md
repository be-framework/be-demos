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

#### [hello-world](./demos/hello-world/)
**Pattern:** Minimal Transformation
**Flow:** `Input → Final`

The simplest possible BE Framework demo. A greeting transformation with no Being or Moment layers.

```text
HelloInput → Hello
```

#### [contact-form](./demos/contact-form/)
**Pattern:** Linear Transformation
**Flow:** `Input → Being → Final`

The simplest BE Framework pattern. An email contact form demonstrating basic input validation, email normalization, and receipt generation.

```text
ContactInput → EmailNormalized → ContactReceived
```

#### [user-registration](./demos/user-registration/)
**Pattern:** Sequential Chain
**Flow:** `Input → Being(A) → Being(B) → Being(C) → Final`

User registration with chained Being transformations: email verification, password hashing, and profile enrichment.

```text
RegistrationInput → EmailVerified → PasswordHashed → ProfileEnriched → UserRegistered
```

### Intermediate Level

#### [order-processing](./demos/order-processing/)
**Pattern:** Diamond Metamorphosis
**Flow:** `Input → [parallel Beings] → [parallel Moments] → Final`

E-commerce order processing with parallel Being chains (Inventory, Payment, Shipping) that produce Moments converging in the Final state.

```text
OrderInput ─┬→ StockLocated → QuantityChecked   → InventoryReserved ─┬→ OrderConfirmed
            ├→ CardValidated → PaymentAuthorized → PaymentCompleted  ─┤
            └→ AddressValidated → CarrierSelected → ShippingArranged ─┘
```

#### [blog-publishing](./demos/blog-publishing/)
**Pattern:** Sequential Chain (3 Beings, 2 Moments)
**Flow:** `Input → Moment → Being → Being → Moment → Being → Final`

Article publishing with staged processing through three Being classes (ArticlePrepared, MarkdownRendered, SlugGenerated) and two Moment classes (ContentPrepared, MetadataResolved), handling markdown rendering, slug generation, excerpt extraction, and author resolution.

```text
ArticleInput → ContentPrepared → ArticlePrepared → MarkdownRendered → MetadataResolved → SlugGenerated → ArticlePublished
```

### Advanced Level

#### [medical-triage](./demos/medical-triage/)
**Pattern:** Branching Metamorphosis
**Flow:** `Input → Being → [Branch] → Final(A) | Final(B) | Final(C)`

Emergency room triage implementing JTAS protocol. One input branches to three possible Finals based on severity assessment.

```text
PatientInput → VitalsMeasured → TriageLevelDetermined
                                        │
              ┌─────────────────────────┼─────────────────────────┐
              ↓                         ↓                         ↓
        [immediate]               [urgent]                  [non-urgent]
              ↓                         ↓                         ↓
     EmergencyAdmitted           UrgentQueued           OutpatientReferred
```

#### [loan-application](./demos/loan-application/)
**Pattern:** Cascade Diamond (2-Stage)
**Flow:** `Input → Stage1(parallel → converge) → Stage2(parallel → Final)`

Mortgage application with staged Moment realization. Stage 1 Moments realize at eligibility confirmation; Stage 2 Moments realize at final approval.

```text
LoanInput → IdentityVerified ─┬→ CreditScored   → CreditApproved   ─┬→ EligibilityConfirmed
                              └→ IncomeAssessed → IncomeApproved   ─┘
                                                                     ↓
                              ┌→ PropertyAppraised → CollateralValued ─┬→ LoanApproved
                              └→ InsuranceQuoted   → InsurancePrepared ─┘
```

#### [insurance-claim](./demos/insurance-claim/)
**Pattern:** Complex Convergence
**Flow:** `Input(A) + Input(B) → converge → parallel(3) → Branch → Final(A) | Final(B)`

Insurance claim processing with multiple input convergence, three-way parallel assessment, and branching finals.

```text
ClaimInput ──┬→ ClaimRegistered ─┬→ ClaimValidated ─┬→ DamageAssessed  ─┬→ [threshold] → ClaimSettled
PolicyInput ─┴→ PolicyVerified  ─┘                  ├→ AdjusterAssigned ─┤              or
                                                    └→ FraudScreened   ─┘              ClaimEscalated
```

## Pattern Summary

| Pattern | Demo | Inputs | Beings | Moments | Finals |
|---------|------|--------|--------|---------|--------|
| Minimal | hello-world | 1 | 0 | 0 | 1 |
| Linear | contact-form | 1 | 1 | 0 | 1 |
| Sequential | user-registration | 1 | 3 | 0 | 1 |
| Diamond | order-processing | 1 | 6 | 6 | 1 |
| Sequential | blog-publishing | 1 | 3 | 2 | 1 |
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
./demos/vendor/bin/phpunit demos/medical-triage/tests/
```

## Requirements

- PHP 8.2+
- [Ray.Di](https://ray-di.github.io/) (dependency injection)

## License

MIT

---

[日本語版はこちら](./README.ja.md)