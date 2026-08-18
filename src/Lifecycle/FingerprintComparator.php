<?php

namespace AlexKassel\ScraperCore\Lifecycle;

use AlexKassel\StableFingerprint\StableFingerprint;

class FingerprintComparator
{
    private StableFingerprint $hasher;

    public function __construct(?StableFingerprint $hasher = null)
    {
        $this->hasher = $hasher ?? new StableFingerprint();
    }

    /**
     * Calculate stable MD5 fingerprint string for a payload.
     */
    public function calculateFingerprint(mixed $payload, array $excludePaths = []): ?string
    {
        if ($payload === null) {
            return null;
        }

        return $this->hasher->hash($payload, $excludePaths, 'md5');
    }

    /**
     * Compare stored fingerprint with current fingerprint.
     * Returns true if fingerprints match (payload unchanged).
     */
    public function matches(?string $storedFingerprint, ?string $currentFingerprint): bool
    {
        if ($storedFingerprint === null || $currentFingerprint === null) {
            return false;
        }

        return hash_equals($storedFingerprint, $currentFingerprint);
    }
}
