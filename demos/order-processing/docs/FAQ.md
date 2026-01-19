# FAQ

Frequently asked questions about Be Framework.

## Design Questions

### Q: Should Reason hold state?

**A: No. Reason should be stateless.**

Reason is a "gate to transcendence" and does not hold state. State belongs to Moment.

```php
// NG: Reason holds state
final class PaymentGateway
{
    private array $authorizations = [];

    public function authorize(...): string
    {
        $this->authorizations[$authCode] = [...];  // Holds state
    }
}

// OK: Moment holds state
final class PaymentGateway
{
    public function authorize(...): PaymentCapture
    {
        return new PaymentCapture($authCode, fn () => $this->capture(...));
    }
}
```

---

### Q: Where should provisional state be?

**A: The existence of Moment itself is the provisional state.**

States like "provisional reservation" or "provisional payment" are expressed by **object existence**, not variables or flags.

- Moment exists = Ready (potentiality)
- Final exists = Realized (actuality)

---

### Q: How do I implement rollback?

**A: Use the "provisional state → realization" pattern instead of rollback.**

In Be Framework, state is not rolled back - if Moment's `be()` is not called, it simply isn't realized.

```text
Moment created → Provisional state (dynamis)
be() called → Realized (energeia)
be() not called → Not realized (naturally expires)
```

---

### Q: What's the difference between `#[Input]` and `#[Inject]`?

**A: The source of the data differs.**

| Attribute | Source | Meaning |
|-----------|--------|---------|
| `#[Input]` | Source object | Flows from immanence (internal) |
| `#[Inject]` | DI container | Injection of transcendence (Reason, etc.) |

```php
final readonly class PaymentCompleted
{
    public function __construct(
        #[Input] public string $cardNumber,   // Flows from Input
        #[Input] public int $amount,          // Flows from Input
        #[Inject] PaymentGateway $gateway,    // Injected from DI container
    ) { }
}
```

---

### Q: Does ALPS enforce constraints?

**A: No. ALPS defines semantics, not constraints.**

ALPS is an ontology that defines vocabulary and relationships. Constraints and validation are handled in a separate layer.

---

### Q: How do I implement lazy evaluation?

**A: Express it as a Moment.**

Express the processing you want to lazily evaluate as a Moment and execute it with `be()`.

```php
final class LazyComputation implements MomentInterface
{
    private $compute;

    public function __construct(callable $compute)
    {
        $this->compute = $compute;
    }

    public function be(): void
    {
        ($this->compute)();
    }
}
```

---

## Conceptual Questions

### Q: What's the difference between Being and Moment?

**A: Moment can have dynamis (potential).**

| | Being | Moment |
|---|-------|--------|
| Dynamis | None | Possible |
| `be()` | None | Optional |
| Nature | Pure transformation | Part of Final |

Being is an intermediate step in transformation. Moment is a part (aspect) of Final, and if it has Potential, it can be realized via `be()`.

---

### Q: Is MomentInterface required?

**A: No. It's optional.**

Only Moments with Potential implement MomentInterface.

```php
// Moment with Potential → implements MomentInterface
final readonly class InventoryReserved implements MomentInterface
{
    public function be(): void { ... }
}

// Moment without Potential → no interface needed
final readonly class CustomerInfo
{
    // No be() - pure data part
}
```

Not all Moments need `be()`.

---

### Q: Is Moment a command to external entities?

**A: No. Moment is a part of self.**

Be Framework is a framework of self-generation. Final's `be()` call is not a command but self-completion.

```text
Final ≠ Commander → Soldier (command)
Final = The whole completes itself through its parts
```

When speaking, breath, vocal cords, and tongue are not external but parts of yourself. Similarly, for OrderConfirmed, Inventory, Payment, and Shipping are parts of itself.

---

### Q: Why the name `be()`?

**A: To express "to exist" and "to become" in a single word.**

- The class name defines "what to become" (PaymentCapture = existence that confirms payment)
- `be()` commands "become that"

```php
$payment->capture->be();  // "Become" as payment confirmation
```

"Existence precedes realization" - born, given purpose, and then becomes.

---

### Q: What are the naming conventions for Reason?

**A: Choose based on role.**

| Role | Pattern | Example |
|------|---------|---------|
| External API connection | Gateway, Client | PaymentGateway |
| Judgment/Evaluation | Protocol, Policy | JTASProtocol |
| Conversion/Calculation | Calculator, Resolver | TaxCalculator |
| Validation | Validator | AddressValidator |

Names that express the role are more appropriate than unified naming.

---

## Testing Questions

### Q: Is testability okay?

**A: Reason is bound via interfaces and can be mocked.**

```php
// Interface definition
interface PaymentGatewayInterface
{
    public function authorize(...): PaymentCapture;
}

// In tests
$mock = $this->createMock(PaymentGatewayInterface::class);
$mock->method('authorize')->willReturn(new PaymentCapture(...));
```

Being/Moment/Final depend on Reason, and Reason is bound via interfaces, so they can be replaced in tests.
