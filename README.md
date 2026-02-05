# Be Framework Demos

**Be, Don't Do.**

A collection of demos showcasing [Be Framework](https://be-framework.github.io/) - an ontological programming framework.

## What is Be Framework?

Traditional OOP centers on "what to do" (Do). Be Framework centers on "what to be" (Be).

```
Traditional: OrderService.processOrder()  ← Verb (Do)
Be:          OrderInput → OrderConfirmed  ← Noun transformation (Be)
```

Objects don't perform actions—they undergo metamorphosis.

## Demos

| Demo | Description | Complexity | Pattern |
|------|-------------|------------|---------|
| [hello-world](demos/hello-world/) | Simplest possible transformation | Beginner | Linear |
| [contact-form](demos/contact-form/) | Semantic validation and Being transformation | Beginner | Linear (Input→Being→Final) |
| [user-registration](demos/user-registration/) | Being chain with sequential dependencies | Intermediate | Sequential Chain |
| [blog-publishing](demos/blog-publishing/) | Potential-less Moments as pure data parts | Intermediate | Linear + Merge |
| [order-processing](demos/order-processing/) | Diamond Metamorphosis with parallel pipelines | Advanced | Diamond |
| [medical-triage](demos/medical-triage/) | Branching metamorphosis with multiple Finals | Advanced | Branching |
| [loan-application](demos/loan-application/) | Cascade pattern with two-stage diamond | Advanced | Cascade |
| [insurance-claim](demos/insurance-claim/) | Multiple Inputs, full concept integration | Expert | Complex |

### Learning Path

```
hello-world ──→ contact-form ──→ user-registration ──→ blog-publishing
                                                              │
                                    order-processing ←────────┘
                                          │
                              ┌───────────┴───────────┐
                              ↓                       ↓
                       medical-triage          loan-application
                              │                       │
                              └───────────┬───────────┘
                                          ↓
                                   insurance-claim
```

### What Each Demo Teaches

| Demo | New Concept |
|------|-------------|
| hello-world | `#[Be]`, `#[Input]`, `#[Inject]`, basic Reason |
| contact-form | Multiple Semantics, Being transformation, pure Reason logic |
| user-registration | Being chain (A→B→C), Interface-based Reason, sequential dependencies |
| blog-publishing | Moment WITHOUT Potential, Moments as pure data parts of a whole |
| order-processing | Diamond pattern, Potential-bearing Moments, `be()` self-completion |
| medical-triage | `#[Be]` with multiple Finals, Policy Reason, type routing |
| loan-application | Cascade (two-stage diamond), staged Moment realization |
| insurance-claim | Multiple Input convergence, all BE concepts unified |

## Getting Started

Each demo is self-contained. Navigate to a demo directory and run:

```bash
cd demos/hello-world
composer install
./vendor/bin/phpunit
```

## Philosophical Foundations

Be Framework implements concepts from six philosophers:

| Concept | Philosopher | Framework Element |
|---------|-------------|-------------------|
| δύναμις (Potentiality) | Aristotle | Input |
| Dasein (Being-there) | Heidegger | Being |
| Moment (Aspect of whole) | Hegel | Moment |
| ἐνέργεια (Actuality) | Aristotle | Final |
| Sinn (Sense) | Frege | Semantic |
| Sufficient Reason | Leibniz | Reason |

## Core Insight

> **"Existence precedes realization"**

A Moment is born, given purpose (class name), and then becomes through `be()`.

This is the meaning of **Be, Don't Do**.

## Learn More

- [Be Framework Documentation](https://be-framework.github.io/)
- [ALPS (Application-Level Profile Semantics)](http://alps.io/)

## License

MIT