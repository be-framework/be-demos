# Moment

The philosophical concept and code role of "Moment" in Be Framework.

## The Trinity

A Moment has three aspects:

```text
Moment
  │
  ├── Dynamis (δύναμις) ─── Realizable potential
  │
  ├── Moment (Aspect) ───── Essential element for the whole
  │
  └── Part of Self ──────── Inner part, not external
```

### 1. Dynamis (Potentiality)

Aristotelian "δύναμις" - the state awaiting actualization.

- Moment = Potentiality (capable state)
- `be()` = Transition to actuality (ἐνέργεια)

### 2. Moment (Essential Aspect)

Hegelian "Moment" - an indispensable element constituting the whole.

```text
OrderConfirmed (Final/Whole)
    ├── InventoryReserved  (Moment/Aspect)
    ├── PaymentCompleted   (Moment/Aspect)
    └── ShippingArranged   (Moment/Aspect)
```

The whole cannot exist unless all aspects are present.

### 3. Part of Self

A Moment is not external but an **inner part of Final**.

```text
Final ≠ Commander → Soldier (command)
Final = The whole completes itself through its parts
```

## be() as Self-Completion

Be Framework is a framework of self-generation. Calling `be()` on a Moment is **not a command but self-completion**.

```php
final readonly class OrderConfirmed
{
    public function __construct(
        public InventoryReserved $inventory,  // Part of self
        public PaymentCompleted $payment,     // Part of self
        public ShippingArranged $shipping,    // Part of self
    ) {
        // Not commands to external entities
        // Self-completion
        $this->inventory->be();
        $this->payment->be();
        $this->shipping->be();
    }
}
```

### Analogy: Speaking

```text
"Speaking" as a whole (Final)
    │
    ├── Breath (Moment) ─── Part of self, potential for vocalization
    ├── Vocal cords (Moment) ─── Part of self, potential for sound
    └── Tongue/lips (Moment) ─── Part of self, potential for words

    └── All be() simultaneously → "Speaking" is realized
```

Breath, vocal cords, and tongue are not external but parts of yourself.
"Speaking" is not a command to external entities but self-realization through coordination of one's parts.

## Simultaneous Realization

All Moment `be()` calls must succeed together.

```text
Inventory reserved ─┐
Payment completed ──┼── Cannot exist as "Order" without all three
Shipping arranged ──┘
```

If any one is missing, the whole (Final) cannot exist.
Partial realization means absence of the whole.

## Difference Between Being and Moment

| | Being | Moment |
|---|-------|--------|
| Role | Intermediate transformation | Part of Final (aspect) |
| Dynamis | None | Possible |
| `be()` | None | Optional |

```php
// Being - Intermediate transformation
final readonly class PaymentAuthorized
{
    // Transformation completed in constructor
}

// Moment (with Potential) - has be()
final readonly class PaymentCompleted implements MomentInterface
{
    public function be(): void
    {
        $this->capture->be();
    }
}

// Moment (without Potential) - no be() needed
final readonly class CustomerInfo
{
    // Pure data part, no be()
}
```

**MomentInterface is optional.** Only Moments with Potential implement it.

## Lifecycle

```text
Born            → Generated from Reason
Given purpose   → Class name defines essence
Becomes         → Realized via be() (as part of Final's self-completion)
```

**"Existence precedes realization"**

A Moment exists. Its purpose (essence) is defined by the class name.
But it has not yet been realized. Only when Final is generated does `be()` make it actual.

## Code Representation

### MomentInterface

```php
interface MomentInterface
{
    public function be(): void;
}
```

`be()` - A single method expressing "to become realized."

### Moment Class

```php
final readonly class InventoryReserved implements MomentInterface
{
    public InventoryReservation $reservation;

    public function __construct(
        #[ProductId] public string $productId,
        #[Quantity] public int $quantity,
        #[WarehouseId] public string $warehouseId,
        #[Inject] InventoryReserver $reserver,
    ) {
        $this->reservation = $reserver->lock($warehouseId, $productId, $quantity);
    }

    public function be(): void
    {
        $this->reservation->be();
    }
}
```

## State Management

State is not stored in variables. **The existence of objects itself** represents state.

- Moment exists = Ready (potentiality)
- Final exists = Realized (actuality)

## Summary

| Concept | Meaning | Code |
|---------|---------|------|
| Moment | Aspect + Dynamis + Part of Self | `implements MomentInterface` |
| be() | Self-completion | Called when Final is generated |
| Final | Self-realization of the whole | Completed by all Moments' `be()` |

In Be Framework, a Moment is not a target of external commands.
**It is a part of self and an essential aspect in the whole's self-realization**.
