<?php

declare(strict_types=1);

namespace Be\Demo\ContactForm\Reason;

/**
 * EmailNormalizer - Reason class
 *
 * Provides the sufficient reason for email normalization.
 * Lowercases the entire address and removes +alias from the local part.
 */
final class EmailNormalizer
{
    public function normalize(string $email): string
    {
        $email = strtolower($email);
        [$local, $domain] = explode('@', $email, 2);

        // Remove +alias from local part
        $plusPos = strpos($local, '+');
        if ($plusPos !== false) {
            $local = substr($local, 0, $plusPos);
        }

        return "{$local}@{$domain}";
    }
}
