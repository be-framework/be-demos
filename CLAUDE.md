# CLAUDE.md — Reading Guide for AI Assistants

This file is the **entry point for AI coding assistants** (Claude, Cursor, Copilot, etc.)
working with this repository. It documents the invariants, conventions, and reading
order you need in order to understand and generate BE Framework code correctly.

Humans should read [`README.md`](./README.md) first. AIs should read **this file**
first, then jump to the representative files linked below.

---

## 1. What this repository is

A collection of PHP demos illustrating the **BE Framework** ontological style
("Be, Don't Do"). Each demo models a workflow as a chain of **typed, immutable
states** that *become* the next state via constructor-injected dependencies —
not as procedural methods that *do* things.

All demos share the same six-layer vocabulary:

| Layer      | Greek / German          | Role                                                         |
|------------|-------------------------|--------------------------------------------------------------|
| Input      | δύναμις (Dynamis)       | Raw potential entering the system.                           |
| Being      | Dasein                  | Existential state with computed properties.                  |
| Moment     | 契機 (Keiki)             | Transitional part-whole carrying a deferred Potential.       |
| Final      | ἐνέργεια (Energeia)     | Fully actualized result; convergence point.                  |
| Semantic   | Sinn                    | Domain validation rules (`#[Validate]`).                     |
| Reason     | Sufficient Reason       | Business logic and external integrations (injected).         |

See [`docs/GLOSSARY.md`](./docs/GLOSSARY.md) for a term-to-file reverse index, and
[`docs/patterns.json`](./docs/patterns.json) for a machine-readable catalog of
all eight demos and their flow shapes.

### Relationship to `be-framework/be-skills`

This repository is the **canonical reference target** for the `be` skill in
[`be-framework/be-skills`](https://github.com/be-framework/be-skills). When
that skill instructs an agent to "see be-demos for examples" or "copy the
skeleton verbatim," **this** is the repository it points at. If you are
running with the `be` skill installed, you should treat the files under
`demos/*/src/` as the ground truth for what idiomatic BE Framework code
looks like, and use this `CLAUDE.md` as the contract for the invariants
those files obey.

---

## 2. Recommended reading order

Read in this order. Each step adds exactly one new concept.

1. `demos/hello-world/src/Input/HelloInput.php` — the minimal `#[Be]` attribute.
2. `demos/hello-world/src/Final/Hello.php` — a Final with no Being/Moment.
3. `demos/contact-form/src/Being/EmailNormalized.php` — a Being transformation
   with `#[Input]` parameters and a `#[Inject]`ed Reason service.
4. `demos/contact-form/src/Semantic/Email.php` — `#[Validate]` and domain
   exceptions.
5. `demos/user-registration/src/Being/*.php` — a three-step linear Being chain.
6. `demos/order-processing/src/Moment/InventoryReserved.php` — the canonical
   Moment (constructor creates a Potential; `be()` commits it).
7. `demos/order-processing/src/Final/OrderConfirmed.php` — diamond
   convergence: multiple Moments self-complete inside the Final constructor.
8. `demos/order-processing/docs/PHILOSOPHY.md` — the conceptual rationale.

Stop here. After step 8 you understand ~80% of every other demo. The advanced
demos (`medical-triage`, `loan-application`, `insurance-claim`) only compose
the same primitives at greater scale.

---

## 3. Hard invariants for generated code

When you write a new Input / Being / Moment / Final / Semantic / Reason class,
**these rules are non-negotiable**. Violating any of them produces code that
will not run under Ray.Di or will break framework expectations.

1. **`final readonly class`** — every state class (Input, Being, Moment, Final)
   MUST be declared `final readonly`. Never emit a mutable property, never omit
   `final`.
2. **`declare(strict_types=1);`** on every file, directly after the opening tag.
3. **`#[Be([Next::class])]`** — every Input and every non-terminal Being MUST
   carry a `#[Be]` attribute naming its successor(s). Finals do NOT carry `#[Be]`.
4. **Constructor parameter order**: `#[Input]` parameters first, `#[Inject]`
   parameters last. Never interleave.
5. **Moments**: implement `MomentInterface`; create the Potential object in
   the constructor; commit it in `be()`. Do NOT call `be()` from the
   constructor. The owning Final calls `be()` on its Moments during self-
   completion.
6. **Finals that converge Moments**: take each Moment via `#[Inject]` and call
   `$this->foo->be()` in the constructor body, then derive the actualized
   fields. See `demos/order-processing/src/Final/OrderConfirmed.php` for the
   canonical shape.
7. **Semantic validators**: one class per concept, one `#[Validate]` method,
   throw a domain exception from `src/Exception/`. Link to schema.org in the
   docblock when a standard term exists (`@link https://schema.org/…`).
8. **Reason services**: always define an `…Interface` and depend on the
   interface, never the concrete class. Ray.Di binds the implementation.
9. **Namespaces**: follow the existing per-demo pattern
   (`Be\Demo\<Name>\<Layer>\…` or `Be\App\<Layer>\…`). Never invent a new
   root namespace.
10. **No side effects in Beings.** A Being transforms data; external I/O
    belongs in an injected Reason service.

---

## 4. Common mistakes to avoid

1. **Calling `be()` in a Moment's constructor.** The constructor builds the
   Potential; `be()` is called later by the enclosing Final. Mixing these
   collapses the two-phase commit shape that makes the diamond pattern work.
2. **Making a Being that reaches into a database directly.** Put the I/O
   in a `Reason` interface, inject it, and keep the Being a pure
   transformation of its `#[Input]` fields.
3. **Forgetting `#[Be]` on an intermediate state.** Without it, Ray.Di has no
   successor to materialize; the chain terminates early and tests silently
   return the wrong class.

---

## 5. Templates

Copy-pasteable minimal skeletons for every layer live in
[`docs/templates/`](./docs/templates/). They are intentionally outside any
Composer autoload scope (`namespace Be\Template;`) so they do not pollute demo
class maps.

---

## 6. Running tests

```bash
composer test                                     # all demos
./demos/vendor/bin/phpunit demos/medical-triage/tests/   # one demo
```

If you modify a demo, always run its tests before concluding the task.
