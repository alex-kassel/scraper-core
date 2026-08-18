<?php

namespace AlexKassel\ScraperCore\Services;

use Illuminate\Contracts\Cache\Lock;
use Illuminate\Support\Facades\Cache;
use AlexKassel\ScraperCore\Exceptions\LockAcquisitionFailedException;

class ExecutionLockManager
{
    private array $activeLocks = [];

    public function acquire(string $domainSlug, string $spiderSlug, int $ttlSeconds = 3600): Lock
    {
        $lockKey = $this->formatLockKey($domainSlug, $spiderSlug);
        $lock = Cache::lock($lockKey, $ttlSeconds);

        if (!$lock->get()) {
            throw new LockAcquisitionFailedException($lockKey, $ttlSeconds);
        }

        $this->activeLocks[$lockKey] = $lock;
        return $lock;
    }

    public function release(string $domainSlug, string $spiderSlug): bool
    {
        $lockKey = $this->formatLockKey($domainSlug, $spiderSlug);
        if (isset($this->activeLocks[$lockKey])) {
            $released = $this->activeLocks[$lockKey]->release();
            unset($this->activeLocks[$lockKey]);
            return $released;
        }

        return Cache::lock($lockKey)->release();
    }

    public function forceClear(string $domainSlug, string $spiderSlug): void
    {
        $lockKey = $this->formatLockKey($domainSlug, $spiderSlug);
        Cache::lock($lockKey)->forceRelease();
        unset($this->activeLocks[$lockKey]);
    }

    public function formatLockKey(string $domainSlug, string $spiderSlug): string
    {
        return "scraper:lock:{$domainSlug}:{$spiderSlug}";
    }
}
