# Be Framework - Philosophical Foundations

## Core Thesis

> **"L'existence précède l'essence"** - Existence precedes essence (Sartre)

Be Framework was built first; its philosophical foundations were discovered after. The framework embodies this existentialist principle in its very creation.

## The Six Pillars

### 1. Input → Dynamis (δύναμις)

**Philosopher**: Aristotle

**Concept**: Potentiality - that which has the capacity to become but has not yet actualized.

```php
#[Be([OrderConfirmed::class])]
final readonly class OrderInput { ... }
```

An Input declares what it *can become* (`#[Be]`), but is not yet that thing. It holds the potential for transformation.

---

### 2. Being → Dasein

**Philosopher**: Martin Heidegger

**Concept**: Being-there, existence-in-process - the state of becoming, not yet complete but actively existing.

```php
final readonly class StockLocated { ... }
final readonly class QuantityChecked { ... }
```

Being classes represent intermediate states in the metamorphosis. They are not merely data containers but existential moments in the transformation process.

---

### 3. Moment → 契機 (Moment)

**Philosopher**: G.W.F. Hegel

**Concept**: An essential aspect that can only be understood as part of a larger whole. It cannot sustain itself independently.

```php
final readonly class InventoryReserved { ... }  // Moment
final readonly class PaymentCompleted { ... }   // Moment
final readonly class ShippingArranged { ... }   // Moment
```

A Moment is complete within its pipeline but incomplete in the larger context. Just as "sending destination information alone cannot complete a remittance," each Moment requires the others to achieve Final.

> *"When something is contradictory, what this means is that it is not independently self-sustaining on its own terms, and so it can only be comprehended as a moment of a larger whole."*
> — Hegel's Dialectics

---

### 4. Final → Energeia (ἐνέργεια)

**Philosopher**: Aristotle

**Concept**: Actuality - the fully realized state, the completion of potentiality.

```php
final readonly class OrderConfirmed {
    public function __construct(
        #[Moment] InventoryReserved $inventory,
        #[Moment] PaymentCompleted $payment,
        #[Moment] ShippingArranged $shipping,
    ) { ... }
}
```

Final represents the actualization of Input's potential. All Moments have converged; the transformation is complete.

---

### 5. Semantic → Sinn + 言霊

**Philosophers**: Gottlob Frege + Japanese tradition

**Concepts**:
- **Sinn** (Frege): The sense or meaning of an expression, distinct from its reference
- **言霊** (Kotodama): The spiritual power that resides in words

```php
final class CardNumber {
    #[Validate]
    public function validate(string $cardNumber): void { ... }
}
```

Semantic classes embody the meaning and power of values. A card number is not just a string—it carries semantic weight. Invalid values are rejected because they lack the proper "spirit" to participate in the metamorphosis.

Frege distinguished between Sinn (sense/meaning) and Bedeutung (reference). Semantic validation ensures values have valid Sinn before they can be used.

---

### 6. Reason → Sufficient Reason

**Philosopher**: Gottfried Wilhelm Leibniz

**Concept**: The Principle of Sufficient Reason - nothing exists without a reason for its existence.

```php
final class WarehouseLocator { ... }    // Why: to find where stock exists
final class PaymentGateway { ... }      // Why: to process monetary transactions
final class ShippingArranger { ... }    // Why: to arrange physical delivery
```

Reason classes embody *raison d'être* - the reason for being. Each encapsulates the "why" of a particular aspect of the system. They are not mere utilities but existential necessities.

> *"No fact can be real or existing and no statement true without a sufficient reason for its being so and not otherwise."*
> — Leibniz

---

## The Diamond Pattern

The Diamond Metamorphosis represents a synthesis of these philosophical concepts:

```
     Dynamis (Input)
          │
    ┌─────┼─────┐
    ↓     ↓     ↓
  Dasein Dasein Dasein  (Being - parallel becoming)
    ↓     ↓     ↓
  契機   契機   契機    (Moment - partial actualization)
    └─────┼─────┘
          ↓
    Energeia (Final)
```

This pattern shows how:
1. A single potentiality can spawn multiple paths of becoming
2. Each path must reach its Moment (partial completion)
3. Only through convergence (dialectical synthesis) does full actuality emerge

---

## Summary Table

| Concept | Directory | Philosopher | Term | Meaning |
|---------|-----------|-------------|------|---------|
| Input | `Input/` | Aristotle | Dynamis | Potentiality |
| Being | `Being/` | Heidegger | Dasein | Being-in-becoming |
| Moment | `Moment/` | Hegel | 契機 | Essential aspect of whole |
| Final | `Final/` | Aristotle | Energeia | Actuality |
| Semantic | `Semantic/` | Frege + 言霊 | Sinn | Meaning with power |
| Reason | `Reason/` | Leibniz | Raison d'être | Sufficient reason |

---

## On Framework Design

Be Framework demonstrates that practical software engineering can embody deep philosophical concepts. The mapping was not forced—it emerged naturally from the problem domain.

This suggests that good abstractions in programming often align with timeless philosophical insights about existence, change, and meaning.

> *"The limits of my language mean the limits of my world."*
> — Ludwig Wittgenstein

By expanding our vocabulary (Input, Being, Moment, Final, Semantic, Reason), we expand what we can express in code.
