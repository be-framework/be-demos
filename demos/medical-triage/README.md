# Medical Triage Demo - Branching Metamorphosis

**Level:** Advanced

This demo demonstrates **branching metamorphosis** in the BE Framework: one Input with multiple possible Finals, where a Policy Reason determines which path is taken.

## Flow

```
PatientInput → VitalsMeasured(Being) → TriageLevelDetermined(Being)
    ├── immediate  → EmergencyAdmitted(Final) [BedAssigned + TeamAlerted Moments with Potential]
    ├── urgent     → UrgentQueued(Final)       [QueuePositioned Moment, no Potential]
    └── non-urgent → OutpatientReferred(Final)  [ReferralCreated Moment, no Potential]
```

## Key Concepts

### 1. Multiple Finals from One Input

Unlike the order-processing demo (diamond metamorphosis with one Final), this demo shows that a single `PatientInput` can become one of three different Finals. The branching happens on `TriageLevelDetermined`, whose `$being` discriminator (an `ImmediatePath|UrgentPath|NonUrgentPath` union) is used by the Be Framework's type matcher (`Be\Framework\BecomingType`) to pick the right Final:

```php
#[Be([EmergencyAdmitted::class, UrgentQueued::class, OutpatientReferred::class])]
final readonly class TriageLevelDetermined
{
    public ImmediatePath|UrgentPath|NonUrgentPath $being;
    // ...
}

final readonly class EmergencyAdmitted
{
    public function __construct(
        #[Input] public ImmediatePath $being,  // type match -> this Final wins
        // ...
    ) { ... }
}
```

This mirrors the `$being` type-matching pattern from the Be Framework's `BeGreeting` example.

### 2. Policy Reason (JTASProtocol)

The `JTASProtocol` is the key Reason that determines which Final path the patient takes. It implements the Japanese Triage and Acuity Scale, assessing:

- Chief complaint severity
- Consciousness level (JCS)
- Vital sign abnormalities

This is a **Policy Reason** - it encodes domain rules that determine the branching logic.

### 3. Moments With and Without Potential

The demo contrasts two types of Moments:

**With Potential (Emergency path):**
- `BedAssigned` holds a `BedReservation` potential - bed must be confirmed
- `TeamAlerted` holds a `TeamAlert` potential - team must be dispatched
- Both are realized via `be()` in `EmergencyAdmitted`

**Without Potential (Urgent/Non-urgent paths):**
- `QueuePositioned` is pure data - just calculates position and wait time
- `ReferralCreated` is pure data - just determines department and creates ID
- No `be()` needed - no side effects to realize

### 4. JCS (Japan Coma Scale)

The consciousness level uses the Japan Coma Scale:

| JCS | Description |
|-----|-------------|
| 0 | Alert |
| 1-3 | Awake but confused |
| 10-30 | Responds to stimuli |
| 100-300 | Deep coma |

## Layer Structure

```
src/
├── Input/          PatientInput (entry point)
├── Being/          VitalsMeasured, TriageLevelDetermined
│   └── Path/       ImmediatePath, UrgentPath, NonUrgentPath ($being discriminators)
├── Moment/         BedAssigned, TeamAlerted, QueuePositioned, ReferralCreated
│   └── Potential/  BedReservation, TeamAlert
├── Final/          EmergencyAdmitted, UrgentQueued, OutpatientReferred
├── Semantic/       Temperature, HeartRate, BloodPressureSystolic, BloodPressureDiastolic,
│                   ConsciousnessLevel, PatientId, ChiefComplaint, VitalsSeverity,
│                   TriageLevel, TriageCode, Being
├── Reason/         VitalsAssessor, JTASProtocol, BedAllocator, TeamDispatcher, ReferralPolicy
├── Exception/      Domain exceptions with en/ja messages
└── Module/         AppModule (DI configuration)
```

## Running Tests

```bash
composer install
./vendor/bin/phpunit tests/
```
