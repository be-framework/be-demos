# Medical Triage Demo - Branching Metamorphosis

**Level:** Advanced
**Pattern focus:** branching by `$being` type matching.

This demo shows **branching metamorphosis** in the BE Framework: one Input
with multiple possible Finals, where a Policy Reason determines which path
is taken. It is intentionally narrow — no Moments, no Potentials, no
parallel Being chains — so the branching pattern stands on its own. For the
Moment/Potential pattern see `demos/order-processing/`.

## Flow

```
PatientInput → TriageLevelDetermined
                   ├── ImmediateCase  → EmergencyAdmitted
                   ├── UrgentCase     → UrgentQueued
                   └── NonUrgentCase  → OutpatientReferred
```

## Key Concepts

### 1. One Input, three Finals

`PatientInput` carries the minimum triage intake: patient id, chief
complaint, and JCS consciousness level. It becomes `TriageLevelDetermined`,
whose `$being` discriminator — typed as a union of three Reason strategies —
is used by the Be Framework's type matcher (`Be\Framework\BecomingType`) to
pick the right Final:

```php
#[Be([EmergencyAdmitted::class, UrgentQueued::class, OutpatientReferred::class])]
final readonly class TriageLevelDetermined
{
    public ImmediateCase|UrgentCase|NonUrgentCase $being;

    public function __construct(
        #[Input] public string $patientId,
        #[Input] public string $chiefComplaint,
        #[Input] public int    $consciousnessLevel,
        #[Inject] JTASProtocol $protocol,
    ) {
        $this->being = match ($protocol->assess($chiefComplaint, $consciousnessLevel)) {
            'immediate'  => new ImmediateCase(),
            'urgent'     => new UrgentCase(),
            'non-urgent' => new NonUrgentCase(),
        };
    }
}

final readonly class EmergencyAdmitted
{
    public function __construct(
        #[Input] public ImmediateCase $being,   // type match -> this Final wins
        #[Input] public string $patientId,
    ) {
        $result = $being->admit($patientId);   // strategy delegation
        // ...
    }
}
```

This is exactly the `$being` type-matching pattern from the framework's
`BeGreeting` example — the three `Case` classes play the role that
`FormalStyle` / `CasualStyle` play there.

### 2. Strategy in the Reason layer

`ImmediateCase`, `UrgentCase`, and `NonUrgentCase` live under `src/Reason/`.
They are not empty markers: each one carries the behavior the matching Final
delegates to (`admit()`, `queue()`, `refer()`). Putting the behavior on the
discriminator keeps the Final constructors tiny and makes each branch a
self-contained unit of meaning.

### 3. Policy Reason (JTASProtocol)

`JTASProtocol` is the Policy Reason that decides which branch runs. It
implements a minimal slice of the Japan Triage and Acuity Scale, looking at
the chief complaint and the JCS consciousness level. A real ER protocol
also considers vital signs; those are deliberately omitted here so the
demo's surface area stays small.

### 4. JCS (Japan Coma Scale)

| JCS     | Description              |
|---------|--------------------------|
| 0       | Alert                    |
| 1-3     | Awake but confused       |
| 10-30   | Responds to stimuli      |
| 100-300 | Deep coma                |

## Layer Structure

```
src/
├── Input/          PatientInput (entry point, 3 fields)
├── Being/          TriageLevelDetermined (sets $being discriminator)
├── Final/          EmergencyAdmitted, UrgentQueued, OutpatientReferred
├── Reason/         JTASProtocol (policy), ImmediateCase/UrgentCase/NonUrgentCase (strategies)
├── Semantic/       PatientId, ConsciousnessLevel, ChiefComplaint, Being
├── Exception/      InvalidPatientIdException, InvalidConsciousnessException
└── Module/         AppModule (DI configuration)
```

## Running Tests

```bash
composer install
./vendor/bin/phpunit tests/
```
