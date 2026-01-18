# Order Processing - Diamond Metamorphosis Demo

## Overview

This demo showcases Be Framework's ability to handle complex, parallel processing pipelines that converge into a single final state - what we call **Diamond Metamorphosis**.

```text
                    OrderInput
                        │
                    doOrder
                        │
        ┌───────────────┼───────────────┐
        ↓               ↓               ↓
   [inventory]     [payment]      [shipping]
        │               │               │
   StockLocated    CardValidated  AddressValidated
        ↓               ↓               ↓
   QuantityChecked PaymentAuth    CarrierSelected
        ↓               ↓               ↓
   ════════════════════════════════════════════
   InventoryReserved PaymentCompleted ShippingArranged
        │               │               │
        └───────────────┼───────────────┘
                        ↓
                  OrderConfirmed
```

## The Problem: Spaghetti Code

Traditional order processing leads to:

- **250+ lines** of nested try-catch blocks
- **Manual state tracking** with boolean flags
- **Exponential rollback complexity** (each failure must undo all previous successes)
- **Scattered validation** mixed with business logic
- **Untestable monoliths**

See: [docs/comparison/Before_Traditional.php](comparison/Before_Traditional.php)

## The Solution: Ontological Metamorphosis

Be Framework expresses the same logic as:

- **Focused classes** (~15-20 lines each)
- **Declarative convergence** through the type system
- **Automatic rollback** handled by the framework
- **Semantic validation** encapsulated and reusable
- **Perfectly testable** units

See: [docs/comparison/After_BeFramework.php](comparison/After_BeFramework.php)

## Directory Structure

```text
src/
├── Input/           意図 (Intention)
│   └── OrderInput.php
│
├── Being/           生成中 (Becoming)
│   ├── Inventory/
│   │   ├── StockLocated.php
│   │   └── QuantityChecked.php
│   ├── Payment/
│   │   ├── CardValidated.php
│   │   └── PaymentAuthorized.php
│   └── Shipping/
│       ├── AddressValidated.php
│       └── CarrierSelected.php
│
├── Moment/          部分完了 (Partial Completion)
│   ├── InventoryReserved.php
│   ├── PaymentCompleted.php
│   └── ShippingArranged.php
│
├── Final/           終端 (Terminal State)
│   └── OrderConfirmed.php
│
├── Semantic/        意味 (Meaning)
│   ├── CardNumber.php
│   ├── PostalCode.php
│   └── Quantity.php
│
└── Reason/          存在理由 (Raison d'être)
    ├── WarehouseLocator.php
    ├── InventoryChecker.php
    ├── InventoryReserver.php
    ├── CardValidator.php
    ├── PaymentGateway.php
    ├── AddressValidator.php
    ├── CarrierSelector.php
    └── ShippingArranger.php
```

## ALPS Ontology

The semantic structure is defined in [alps/order.json](../alps/order.json), following the three-layer ALPS model:

1. **Ontology** - Atomic semantic fields (cartId, cardNumber, etc.)
2. **Taxonomy** - States with their available data and transitions
3. **Choreography** - Transitions between states (doOrder, doCheckQuantity, etc.)

## Key Concepts

### Moment (契機)

A **Moment** is a partial completion that:
- Is complete within its own pipeline
- Cannot exist meaningfully without the whole
- Must converge with other Moments to reach Final

From Hegel's dialectic: *"A Moment is an essential aspect of a whole conceived as a static system, and an essential phase in a whole conceived as a dialectical movement."*

Example: `InventoryReserved` is meaningless without `PaymentCompleted` and `ShippingArranged`. Only when all three converge does `OrderConfirmed` (the whole) come into existence.

### Diamond Metamorphosis

The diamond pattern represents:
1. **Fan-out**: One Input spawns multiple parallel pipelines
2. **Independent processing**: Each pipeline transforms through its Being states
3. **Partial completion**: Each pipeline reaches its Moment
4. **Convergence**: All Moments merge into a single Final

This pattern is common in real-world systems:
- Order processing (inventory + payment + shipping)
- User registration (email verification + profile + permissions)
- Loan application (identity + credit + risk assessment)

## Running Tests

```bash
./vendor/bin/phpunit
```

Test coverage includes:
- **Semantic**: Validation logic
- **Reason**: Business logic
- **Being**: Intermediate transformations
- **Moment**: Partial completions
- **Final**: Convergence point
- **Becoming**: Full integration (Input → Final)
