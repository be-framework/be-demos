# Contact Form Demo

A beginner-level Be Framework demo showing multiple Semantic validations, Being transformation, and pure Reason logic.

## What This Demo Shows

```
ContactInput → EmailNormalized(Being) → ContactReceived(Final)
```

A contact form submission is validated, the email is normalized through a Being state, and a receipt is generated in the Final state.

## The Code

### Input (Potentiality)

```php
#[Be([ContactReceived::class])]
final readonly class ContactInput
{
    public function __construct(
        public string $name,
        public string $email,
        public string $subject,
        public string $message,
    ) {}
}
```

The `#[Be]` attribute declares what this Input *can become*.

### Semantic (Validation)

Three Semantic validators ensure data integrity:

- **Email** - Validates email format using `filter_var`
- **SubjectLine** - Validates non-empty and max 200 characters
- **MessageBody** - Validates min 10 and max 5000 characters

```php
final class Email
{
    #[Validate]
    public function validate(string $email): void
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidEmailException();
        }
    }
}
```

### Being (Transformation)

```php
final readonly class EmailNormalized
{
    public string $normalizedEmail;

    public function __construct(
        #[Input] string $email,
        #[Inject] EmailNormalizer $normalizer,
    ) {
        $this->normalizedEmail = $normalizer->normalize($email);
    }
}
```

The Being state transforms the raw email into a normalized form.

### Final (Actuality)

```php
final readonly class ContactReceived
{
    public string $receiptId;
    public string $receivedAt;

    public function __construct(
        #[Input] public string $name,
        #[Input] public string $subject,
        #[Input] public string $message,
        #[Input] public string $normalizedEmail,
        #[Inject] ReceiptGenerator $receipt,
    ) {
        $this->receiptId = $receipt->generate();
        $this->receivedAt = date('Y-m-d\TH:i:sP');
    }
}
```

### Reason (Sufficient Reason)

- **EmailNormalizer** - Lowercases and removes +alias from local part
- **ReceiptGenerator** - Generates unique receipt IDs (`RCP-{date}-{hash}`)

## Usage

```php
$input = new ContactInput(
    name: 'John Doe',
    email: 'John.Doe+newsletter@Example.COM',
    subject: 'Hello',
    message: 'This is my message to you.',
);
$final = ($becoming)($input);

echo $final->normalizedEmail; // "john.doe@example.com"
echo $final->receiptId;       // "RCP-20260205-a1b2c3d4"
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
| Input | ContactInput | Potentiality |
| Semantic | Email, SubjectLine, MessageBody | Validation |
| Being | EmailNormalized | Transformation |
| Final | ContactReceived | Actuality |
| Reason | EmailNormalizer, ReceiptGenerator | Sufficient reason |

## Next Steps

See [order-processing](../order-processing/) for a more complex example with parallel pipelines and Diamond Metamorphosis.
