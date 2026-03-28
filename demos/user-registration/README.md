# User Registration Demo

**Level:** Intermediate
**Pattern:** Being chain (sequential A->B->C), Interface-based Reason

## Overview

This demo shows how a user registration process is modeled as a sequential Being chain in the BE Framework. Each step in the chain transforms and enriches the data until the final `UserRegistered` state is reached.

## Flow

```
RegistrationInput -> EmailVerified(Being) -> PasswordHashed(Being) -> ProfileEnriched(Being) -> UserRegistered(Final)
```

## Architecture

### Input
- **RegistrationInput** - Accepts email, password, and displayName. Declares its potential to become `UserRegistered`.

### Being Chain (Sequential Steps)
1. **EmailVerified** - Verifies the email is not already registered via `EmailVerifierInterface`.
2. **PasswordHashed** - Hashes the plain-text password using bcrypt via `PasswordHasher`.
3. **ProfileEnriched** - Resolves the Gravatar avatar URL via `GravatarResolver`.

### Final
- **UserRegistered** - Convergence point. Generates a user ID and welcome token, combining all results from the Being chain.

### Semantic (Validation)
- **Email** - Validates RFC-compliant email format.
- **Password** - Validates minimum 8 characters with uppercase, lowercase, and digit.
- **DisplayName** - Validates 2-50 characters with no control characters.

### Reason (Services)
- **EmailVerifierInterface / EmailVerifier** - Checks email availability (interface-based for testability).
- **PasswordHasher** - Bcrypt password hashing.
- **GravatarResolver** - Gravatar URL resolution from email.
- **UserIdGenerator** - Unique user ID generation.
- **WelcomeTokenGenerator** - Welcome/verification token generation.

## Key Concepts

- **Being Chain**: Sequential transformation where each Being produces data consumed by downstream Beings or the Final state.
- **Interface-based Reason**: `EmailVerifierInterface` demonstrates how Reason classes can be abstracted behind interfaces for testability and swappability.
- **Semantic Validation**: Input values are validated at the Semantic layer before reaching Beings, ensuring domain invariants are maintained.

## Running

```bash
composer install
./vendor/bin/phpunit
```
