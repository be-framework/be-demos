# Reason

The role and design guidelines for "Reason" in Be Framework.

## What is Reason?

Reason is **where immanence meets transcendence** - the point where internal system data contacts the external world.

```text
Immanence (Internal)           Transcendence
─────────────────────────────────────────────
Internal system data    ←→    External systems / Domain rules
Flows in via #[Input]   │
cardNumber, amount      │
                        │
                     Reason
                     (Gate)
```

## Two Types of Transcendence

Reason connects to two types of "transcendence":

### 1. External Systems

Technical boundaries "outside" the system.

```php
final class PaymentGateway        // Payment API
final class InventoryReserver     // Inventory management system
final class ShippingArranger      // Shipping carrier API
```

### 2. Domain Rules

Universal rules that exist independently of individual instances.

```php
final class JTASProtocol          // Medical triage protocol
final class TaxCalculator         // Tax calculation rules
final class CreditPolicy          // Credit policy
```

## Naming Patterns

Role-based naming is recommended:

| Role | Pattern | Example |
|------|---------|---------|
| External API connection | Gateway, Client | PaymentGateway, InventoryClient |
| Judgment/Evaluation | Protocol, Policy, Evaluator | JTASProtocol, CreditPolicy |
| Conversion/Calculation | Calculator, Resolver, Converter | TaxCalculator, AddressResolver |
| Validation | Validator | AddressValidator |

**Names that express the role are more appropriate than unified naming.**

## Design Principles

### 1. Stateless

Reason holds no state. State belongs to Moment.

```php
// Good - Stateless
final class PaymentGateway
{
    public function authorize(string $cardNumber, int $amount): PaymentCapture
    {
        // No state
        // Returns a Moment
    }
}

// Bad - Holds state
final class PaymentGateway
{
    private array $authorizations = [];  // NG: State

    public function authorize(...): string { ... }
    public function capture(string $authCode): string { ... }
}
```

### 2. Returns Moment

Returns a Moment as the result of interaction with external systems.

```php
public function authorize(string $cardNumber, int $amount): PaymentCapture
{
    $authCode = $this->api->authorize($cardNumber, $amount);

    return new PaymentCapture(
        $authCode,
        $amount,
        fn () => $this->capture($authCode, $amount),
    );
}
```

The Moment contains a callback for `be()`.

### 3. Bind via Interface

For testability, bind through interfaces in the DI container.

```php
// Interface
interface PaymentGatewayInterface
{
    public function authorize(string $cardNumber, int $amount): PaymentCapture;
}

// Implementation
final class PaymentGateway implements PaymentGatewayInterface { ... }

// Binding
$this->bind(PaymentGatewayInterface::class)->to(PaymentGateway::class);

// Can be replaced with mock in tests
```

## Type Match

Reason is also used for type determination by domain logic.

```php
final class JTASProtocol
{
    public function assess(float $temperature, int $heartRate): string
    {
        if ($temperature > 39.0 || $heartRate > 120) {
            return 'emergency';
        }
        return 'observation';
    }
}
```

The return type determines the type of the next Being (Type IS Capability).

## Position of Reason

```text
Input
  │
  ├── Being ← Reason injected (logic needed for transformation)
  │     │
  │     └── Moment ← Generated from Reason (potentiality)
  │           │
  └───────────┴── Final ← Completed by Moment's be()
```

- **Being**: Transforms using Reason
- **Moment**: Born from Reason (returned by Reason's methods)

## Summary

| Property | Description |
|----------|-------------|
| Role | Junction of immanence and transcendence |
| State | Stateless |
| Return value | Moment (potentiality) |
| Binding | Via interface |
| Naming | Choose based on role |

Reason is a "gate" connecting inside and outside. But it holds nothing itself.
