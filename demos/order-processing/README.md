# Be Framework Demo - Order Processing

**Be, Don't Do.**

An ontological programming demo for order processing.

## Significance of This Demo

Traditional object-oriented programming centers on "what to do" (Do). Be Framework centers on "what to be" (Be).

```text
Traditional: OrderService.processOrder()  ← Verb (Do)
Be:          OrderInput → OrderConfirmed  ← Noun transformation (Be)
```

This paradigm shift transforms code from a **state machine** into **existential metamorphosis**.

## Philosophical Foundations

This demo implements concepts from six philosophers:

| Directory | Concept | Philosopher | Meaning |
|-----------|---------|-------------|---------|
| `Input/` | δύναμις | Aristotle | Potentiality |
| `Being/` | Dasein | Heidegger | Being-in-becoming |
| `Moment/` | Moment | Hegel | Essential aspect of whole |
| `Final/` | ἐνέργεια | Aristotle | Actuality |
| `Semantic/` | Sinn + 言霊 | Frege | Meaning with power |
| `Reason/` | Sufficient Reason | Leibniz | Raison d'être |

Details: [docs/PHILOSOPHY.md](docs/PHILOSOPHY.md)

## Diamond Metamorphosis

Order processing follows a "Diamond Metamorphosis" pattern where three parallel pipelines converge into one:

```text
                    OrderInput
                        │
        ┌───────────────┼───────────────┐
        ↓               ↓               ↓
   [inventory]     [payment]      [shipping]
        ↓               ↓               ↓
   InventoryReserved PaymentCompleted ShippingArranged
        │               │               │
        └───────────────┼───────────────┘
                        ↓
                  OrderConfirmed
```

## Moment - The Trinity

The core concept of this demo is **Moment**. A Moment has three aspects:

### 1. Dynamis (δύναμις)

Realizable potential. Becomes actuality (ἐνέργεια) through `be()`.

```php
$moment->be();  // From potential to actual
```

### 2. Moment (契機)

An essential constituent of the whole. Meaningless in isolation.

```text
Inventory reservation alone is not an order
Payment alone is not an order
Shipping arrangement alone is not an order
→ Only when all converge can "Order" exist
```

### 3. Part of Self

A Moment is not external but an **inner part** of Final.

```php
// This is not a command
$this->inventory->be();  // Self-completion
```

When speaking, breath, vocal cords, and tongue are not external—they are parts of yourself. Similarly, for OrderConfirmed, Inventory, Payment, and Shipping are parts of itself.

Details: [docs/MOMENT.md](docs/MOMENT.md)

## Code Examples

### Input (Potentiality)

```php
#[Be([OrderConfirmed::class])]
final readonly class OrderInput
{
    public function __construct(
        public string $cartId,
        public string $cardNumber,
        // ...
    ) {}
}
```

### Moment (契機 + Dynamis)

```php
final readonly class InventoryReserved implements MomentInterface
{
    public InventoryReservation $reservation;

    public function __construct(
        #[ProductId] public string $productId,
        #[Inject] InventoryReserver $reserver,
    ) {
        $this->reservation = $reserver->lock(...);
    }

    public function be(): void
    {
        $this->reservation->be();  // Provisional → Confirmed
    }
}
```

### Final (Actuality)

```php
final readonly class OrderConfirmed
{
    public function __construct(
        #[Inject] public InventoryReserved $inventory,
        #[Inject] public PaymentCompleted $payment,
        #[Inject] public ShippingArranged $shipping,
    ) {
        // Self-completion: realize all parts
        $this->inventory->be();
        $this->payment->be();
        $this->shipping->be();
    }
}
```

## Comparison with Traditional Approach

### Before (Traditional Procedural)

```php
// 250+ lines of spaghetti code
try {
    $inventory = $this->reserveInventory(...);
    try {
        $payment = $this->processPayment(...);
        try {
            $shipping = $this->arrangeShipping(...);
        } catch (Exception $e) {
            $this->refundPayment($payment);
            $this->releaseInventory($inventory);
            throw $e;
        }
    } catch (Exception $e) {
        $this->releaseInventory($inventory);
        throw $e;
    }
} catch (Exception $e) {
    throw $e;
}
```

### After (Be Framework)

```php
// Each class 15-20 lines
// No rollback needed - provisional state → be() confirms
$order = ($becoming)($orderInput);
```

Details: [docs/comparison/](docs/comparison/)

## Installation

```bash
composer install
```

## Running Tests

```bash
./vendor/bin/phpunit
```

## Directory Structure

```text
src/
├── Input/           Intention (Dynamis)
├── Being/           Becoming (Dasein)
├── Moment/          Essential aspect (Moment + Dynamis)
│   └── Potential/   Realizable potential
├── Final/           Terminal state (Energeia)
├── Semantic/        Meaning (Sinn)
├── Reason/          Raison d'être
├── Attribute/       DI binding attributes
└── Module/          Dependency injection config
```

## Documentation

- [PHILOSOPHY.md](docs/PHILOSOPHY.md) - Philosophical foundations
- [MOMENT.md](docs/MOMENT.md) - The Moment concept
- [REASON.md](docs/REASON.md) - Role of Reason
- [FAQ.md](docs/FAQ.md) - Frequently asked questions
- [DEMO.md](docs/DEMO.md) - Demo details

## Core Insight

> **"Existence precedes realization"**

A Moment is born, given purpose (class name), and then becomes through `be()`.

Objects are defined not by "what they do" but by "what they are."
Methods are not actions but realizations of existence.

This is the meaning of **Be, Don't Do**.

## License

MIT
