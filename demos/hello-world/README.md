# Hello World Demo

The simplest possible Be Framework transformation.

## What This Demo Shows

```
HelloInput → Hello
```

An Input with a name transforms into a Final with a greeting.

## The Code

### Input (Potentiality)

```php
#[Be([Hello::class])]
final readonly class HelloInput
{
    public function __construct(
        public string $name,
    ) {}
}
```

The `#[Be]` attribute declares what this Input *can become*.

### Final (Actuality)

```php
final readonly class Hello
{
    public string $greeting;

    public function __construct(
        #[Input] string $name,      // From HelloInput
        #[Inject] Greeting $greeting, // From DI container
    ) {
        $this->greeting = "{$greeting->greeting} {$name}";
    }
}
```

### Reason (Sufficient Reason)

```php
final class Greeting
{
    public string $greeting = 'Hello';
}
```

Why is the greeting "Hello"? Because Greeting says so.

## Usage

```php
$input = new HelloInput(name: 'World');
$final = ($becoming)($input);

echo $final->greeting; // "Hello World"
```

## Installation

```bash
composer install
```

## Running Tests

```bash
./vendor/bin/phpunit
```

## Key Concepts

| Concept | Class | Role |
|---------|-------|------|
| Input | HelloInput | Potentiality (δύναμις) |
| Final | Hello | Actuality (ἐνέργεια) |
| Reason | Greeting | Sufficient reason |

## Next Steps

See [order-processing](../order-processing/) for a more complex example with:
- Parallel pipelines
- Diamond Metamorphosis
- Moment with `be()` pattern
