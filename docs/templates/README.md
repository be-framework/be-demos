# Templates — Minimal skeletons for each BE Framework layer

These six files are copy-paste starting points for every layer of a BE
Framework flow. They are intentionally placed under `namespace Be\Template;`
so that no `composer.json` autoloader picks them up and no real demo depends
on them. They will not be executed; they exist purely as reference shapes
for humans and AI assistants.

Each template:

- Compiles as valid PHP (you can `php -l` it).
- Shows the required attributes, property modifiers, and constructor shape.
- Marks every line you must change with `// TODO: …`.

| File                            | Layer                 | Use when you need …                                             |
|---------------------------------|-----------------------|-----------------------------------------------------------------|
| `InputTemplate.php`             | Input (Dynamis)       | a new entry point that declares its successor.                  |
| `BeingTemplate.php`             | Being (Dasein)        | a pure transformation that enriches state.                      |
| `MomentTemplate.php`            | Moment (Keiki)        | a part-whole whose Potential is committed later by a Final.     |
| `FinalTemplate.php`             | Final (Energeia)      | a convergence point that realizes Moments via self-completion.  |
| `SemanticTemplate.php`          | Semantic (Sinn)       | a domain validator that throws on invalid values.               |
| `ReasonInterfaceTemplate.php`   | Reason (Suff. Reason) | a business-logic contract injected into a Being or Moment.      |

See [`CLAUDE.md`](../../CLAUDE.md) for the invariants every generated class
must obey, and [`GLOSSARY.md`](../GLOSSARY.md) for real canonical examples.
