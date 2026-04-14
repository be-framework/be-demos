# Templates — Minimal skeletons for each BE Framework layer

These six files are copy-paste starting points for every layer of a BE
Framework flow. They live under `docs/templates/`, outside any
`composer.json` autoload path, so no real demo depends on them. They will
not be executed; they exist purely as reference shapes for humans and AI
assistants.

> **Namespace rule.** Each template file uses the placeholder namespace
> `Be\Template;` only because PHP requires the file to declare *some*
> namespace. **When you copy a template into a real demo you MUST replace
> it** with the per-demo pattern — `Be\Demo\<Name>\<Layer>\…` or
> `Be\App\<Layer>\…` — to comply with the repository convention in
> [`CLAUDE.md`](../../CLAUDE.md). Never leave `Be\Template` in committed
> code. Each template marks the line you must change with a `TODO`
> comment on the `namespace` declaration.

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
