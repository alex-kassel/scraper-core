<?php

namespace AlexKassel\ScraperCore\Exceptions;

use AlexKassel\LaravelActionableDiagnostics\Enums\ErrorSeverityEnum;

class LockAcquisitionFailedException extends ScraperCoreException
{
    public function __construct(string $lockKey, int $ttl)
    {
        $message = "Failed to acquire execution lock '{$lockKey}' with TTL {$ttl}s. Another run is active.";
        parent::__construct(
            message: $message,
            severity: ErrorSeverityEnum::OPERATIONAL,
            diagnosticData: [
                'lock_key' => $lockKey,
                'ttl' => $ttl,
            ]
        );
    }

    public function getRemediationSteps(): array
    {
        return [
            "Wait for the active run to complete before starting a new run.",
            "Pass --force option to artisan command if an active lock is stale and needs clear."
        ];
    }
}
