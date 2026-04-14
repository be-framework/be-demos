# Glossary — BE Framework Terms and Canonical Examples

A reverse index from the philosophical vocabulary used throughout this
repository to the concrete PHP file that best illustrates each term. If you
are an AI assistant, this is the fastest way to ground the six-layer model
in actual code.

Paths are relative to the repository root.

---

## Dynamis (δύναμις) — "Input"

**Definition.** The raw potential that enters the system before any
transformation. In code this is an `Input` class: a `final readonly` DTO
that carries only user-supplied data and declares its successor via `#[Be]`.

- **Canonical example**: [`demos/hello-world/src/Input/HelloInput.php`](../demos/hello-world/src/Input/HelloInput.php) — the minimum viable Input.
- **Multi-input example**: [`demos/insurance-claim/src/Input/ClaimInput.php`](../demos/insurance-claim/src/Input/ClaimInput.php) and [`PolicyInput.php`](../demos/insurance-claim/src/Input/PolicyInput.php) — two Inputs that converge.

---

## Dasein — "Being"

**Definition.** An existential state whose properties are computed at
construction time from its `#[Input]` parameters and injected `Reason`
services. A Being has no mutable state and no side effects beyond the
pure transformation captured in its constructor.

- **Canonical example**: [`demos/contact-form/src/Being/EmailNormalized.php`](../demos/contact-form/src/Being/EmailNormalized.php) — an injected normalizer produces `$normalizedEmail` from `$email`.
- **Chain example**: [`demos/user-registration/src/Being/`](../demos/user-registration/src/Being/) — three Beings in series (`EmailVerified → PasswordHashed → ProfileEnriched`).

---

## Keiki (契機) — "Moment"

**Definition.** A transitional part of a larger whole, carrying a deferred
Potential. A Moment implements `MomentInterface`; its constructor creates
the Potential object (e.g. reserving inventory) and its `be()` method commits
it. Moments are Hegelian "moments of a whole" and Aristotelian "dynamis"
simultaneously — they exist *in-order-to* be realized by an enclosing Final.

- **Interface**: [`demos/order-processing/src/Moment/MomentInterface.php`](../demos/order-processing/src/Moment/MomentInterface.php) — the one-method contract.
- **Canonical example**: [`demos/order-processing/src/Moment/InventoryReserved.php`](../demos/order-processing/src/Moment/InventoryReserved.php) — locks inventory in the constructor, commits in `be()`.

---

## Energeia (ἐνέργεια) — "Final"

**Definition.** The fully actualized result; the convergence point at the
end of a flow. A Final that owns Moments realizes them via self-completion:
it takes each Moment through `#[Inject]`, calls `$moment->be()` in its
constructor, and derives its own actualized fields from the committed state.

- **Canonical example**: [`demos/order-processing/src/Final/OrderConfirmed.php`](../demos/order-processing/src/Final/OrderConfirmed.php) — diamond convergence of three Moments.
- **Simple example**: [`demos/contact-form/src/Final/ContactReceived.php`](../demos/contact-form/src/Final/ContactReceived.php) — Final without Moments.
- **Branching example**: [`demos/medical-triage/src/Final/`](../demos/medical-triage/src/Final/) — three mutually exclusive Finals.

---

## Sinn — "Semantic"

**Definition.** Domain validation rules. A Semantic class has a single
`#[Validate]` method that throws a domain-specific exception on bad input.
Semantic classes often link to schema.org via `@link` in the docblock when
a standard term exists.

- **Canonical example**: [`demos/order-processing/src/Semantic/Quantity.php`](../demos/order-processing/src/Semantic/Quantity.php) — validates a quantity integer and links to `https://schema.org/quantity`.
- **String example**: [`demos/contact-form/src/Semantic/Email.php`](../demos/contact-form/src/Semantic/Email.php) — email syntax validation.

---

## Sufficient Reason — "Reason"

**Definition.** The business logic and external integrations that a Being
or Moment needs in order to become what it is. Reason classes are always
injected through an interface (never used concretely), so they can be
replaced in tests. Think of them as the "why this Being is able to exist"
layer — the ground of the transformation.

- **Canonical interface**: [`demos/order-processing/src/Reason/InventoryReserverInterface.php`](../demos/order-processing/src/Reason/InventoryReserverInterface.php) — the contract a Moment depends on.
- **Implementation**: [`demos/order-processing/src/Reason/InventoryReserver.php`](../demos/order-processing/src/Reason/InventoryReserver.php) — the bound concrete service.

---

## Supporting vocabulary

- **Potential**: the object a Moment holds between construction and `be()`.
  Lives under `src/Moment/Potential/` (e.g. `InventoryReservation`). Calling
  its `be()` is the idempotent commit.
- **`#[Be]`**: Framework attribute declaring successor class(es) for an Input
  or Being. Defined in `Be\Framework\Attribute\Be`.
- **`#[Input]`**: Ray\InputQuery attribute marking constructor parameters
  fed from the prior state's public properties.
- **`#[Inject]`**: Ray.Di attribute marking parameters resolved from the DI
  container.
- **`#[Validate]`**: Framework attribute marking the method that runs during
  Semantic validation.
- **Qualifier attributes** (e.g. `#[ProductId]`, `#[Quantity]`): per-demo
  attributes under `src/Attribute/` that act as Ray.Di qualifiers to
  disambiguate parameters of identical primitive types.

See [`CLAUDE.md`](../CLAUDE.md) for the recommended reading order and
hard invariants, and [`patterns.json`](./patterns.json) for the full demo
catalog in machine-readable form.
