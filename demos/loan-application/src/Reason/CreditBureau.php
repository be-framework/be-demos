<?php

declare(strict_types=1);

namespace Be\Demo\LoanApplication\Reason;

use Be\Demo\LoanApplication\Moment\Potential\CreditInquiry;

/**
 * Credit Bureau - Reason (stateless gateway)
 *
 * Scores creditworthiness and creates CreditInquiry moments that can be realized.
 */
final class CreditBureau implements CreditBureauInterface
{
    private int $lastScore = 0;
    private string $lastRating = '';

    /** @return array{score: int, rating: string} */
    public function score(string $applicantId): array
    {
        // Demo: generate score based on applicant ID hash
        $hash = crc32($applicantId);
        $score = 600 + abs($hash % 200); // Range: 600-799
        $rating = match (true) {
            $score >= 750 => 'A',
            $score >= 700 => 'B',
            $score >= 650 => 'C',
            default => 'D',
        };

        $this->lastScore = $score;
        $this->lastRating = $rating;

        return ['score' => $score, 'rating' => $rating];
    }

    public function inquire(): CreditInquiry
    {
        $score = $this->lastScore ?: 720;
        $rating = $this->lastRating ?: 'B';

        return new CreditInquiry(
            $score,
            $rating,
            fn () => $this->finalizeInquiry($score),
        );
    }

    private function finalizeInquiry(int $score): string
    {
        // External system call to finalize credit inquiry record
        // In production: return $this->api->finalizeInquiry($score);
        return sprintf('INQ-%s-%d-%s', date('YmdHis'), $score, substr(md5((string) $score), 0, 6));
    }
}
