# Scraper Core (`alex-kassel/scraper-core`)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alex-kassel/scraper-core.svg?style=flat-square)](https://packagist.org/packages/alex-kassel/scraper-core)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/alex-kassel/scraper-core/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/alex-kassel/scraper-core/actions)
[![Total Downloads](https://img.shields.io/packagist/dt/alex-kassel/scraper-core.svg?style=flat-square)](https://packagist.org/packages/alex-kassel/scraper-core)
[![License](https://img.shields.io/packagist/l/alex-kassel/scraper-core.svg?style=flat-square)](LICENSE)

**`alex-kassel/scraper-core`** is an enterprise scraping engine for PHP 8.4 and Laravel providing Roach PHP integration, atomic item discovery lifecycle management, stable MD5 fingerprint comparison, commit-before-dispatch event intents, watermark persistence buffers, and overlapping run lock management.

---

## Key Features

- **Atomic 5-Step Lifecycle Engine:** Identification, contract validation, fingerprint comparison, watermark persistence, and event intent generation.
- **Stable Fingerprint Integration:** Canonicalized MD5 content hashing adhering to RFC 8785 via `alex-kassel/stable-fingerprint`.
- **Commit-Before-Dispatch Guarantee:** Domain event intents are dispatched to Laravel's Event Dispatcher strictly *after* database transactions commit successfully.
- **Watermark Persistence Buffer (`UnchangedBatchBuffer`):** 500-item bulk threshold for efficient batch timestamp updates on unchanged items.
- **Single-Item Transaction Isolation (`ItemTransactionManager`):** Fail-closed per-item `DB::transaction(...)` boundaries with diagnostic rollback alerts (`ItemTransactionRollbackObserved`).
- **2-Level Lifecycle Extension Topology:** Clean Template Adapter pattern bridging generic platform contracts (`AlexKassel\PlatformContracts\AbstractLifecycleExtension`) with domain-specific spider hooks (`onSpiderRunStarting`, `onItemProcessed`, `onItemDropped`, `onSpiderRunFinished`, `onSpiderRunFailed`).
- **Overlap Lock Management (`ExecutionLockManager`):** Atomic `Cache::lock()` execution locks using pattern `scraper:lock:{domain}:{spider}` with TTL and force-release capabilities.
- **Actionable Diagnostics Integration:** Exception hierarchy implementing `AlexKassel\LaravelActionableDiagnostics\Contracts\ActionableExceptionInterface`.

---

## Installation

Install the package via Composer:

```bash
composer require alex-kassel/scraper-core
```

The Service Provider `AlexKassel\ScraperCore\Providers\ScraperCoreServiceProvider` is automatically registered via Laravel Package Discovery.

---

## Architecture Overview

```text
Spider Execution (Roach PHP)
        │
        ▼
CoreLifecycleBundle (5-Step Engine)
  ├── 1. Seen Tracking & Duplicate Detection
  ├── 2. Contract & Invariant Validation
  ├── 3. Fingerprint Comparison (FingerprintComparator + stable-fingerprint)
  ├── 4. Persistence & Watermark Buffering (UnchangedBatchBuffer / ItemTransactionManager)
  └── 5. Event Intent Generation (Commit-Before-Dispatch)
        │
        ▼
Laravel Event Dispatcher (NewItemObserved, ItemChangedObserved, SpiderRunFinished...)
```

---

## Basic Usage

### 1. Evaluating Item Discovery Lifecycle

```php
use AlexKassel\ScraperCore\Lifecycle\CoreLifecycleBundle;
use AlexKassel\ScraperCore\DTOs\SpiderResult;
use AlexKassel\ScraperCore\Enums\SpiderResultStatus;

$bundle = new CoreLifecycleBundle();

$result = new SpiderResult(
    externalId: 'item-101',
    status: SpiderResultStatus::Available,
    payload: ['title' => 'Sample Product', 'price' => 99.99],
    contentType: 'application/json'
);

// Evaluate item classification against prior stored fingerprint
$decision = $bundle->evaluate($result, storedFingerprint: 'existing_md5_hash');

if ($decision->classification === ItemDiscoveryClassification::ActiveFingerprintDiffers) {
    // Item content changed
}
```

### 2. Implementing Custom Lifecycle Extensions

Extend `AbstractScraperLifecycleExtension` to hook into spider run lifecycle events:

```php
use AlexKassel\ScraperCore\Lifecycle\AbstractScraperLifecycleExtension;
use AlexKassel\ScraperCore\DTOs\SpiderRunContext;
use AlexKassel\ScraperCore\DTOs\ItemDiscoveryDecision;

class CustomSpiderObserverExtension extends AbstractScraperLifecycleExtension
{
    public function onSpiderRunStarting(SpiderRunContext $context): void
    {
        // Run starting logic
    }

    public function onItemProcessed(ItemDiscoveryDecision $decision): void
    {
        // Handle processed item decision
    }

    public function onItemDropped(ItemDiscoveryDecision $decision): void
    {
        // Log dropped item decision
    }
}
```

### 3. Execution Lock Management

Prevent overlapping spider runs:

```php
use AlexKassel\ScraperCore\Services\ExecutionLockManager;

$lockManager = app(ExecutionLockManager::class);

// Acquire lock for spider run (3600s TTL)
$lock = $lockManager->acquire(domainSlug: 'domain-one', spiderSlug: 'spider-one', ttlSeconds: 3600);

try {
    // Execute spider run...
} finally {
    $lockManager->release(domainSlug: 'domain-one', spiderSlug: 'spider-one');
}
```

---

## Testing

Run the PHPUnit test suite:

```bash
composer test
```

Or execute PHPUnit directly:

```bash
vendor/bin/phpunit
```

---

## Security Vulnerabilities

If you discover a security vulnerability, please send an email to Alexander Macenko via `alexander.macenko@gmail.com`.

---

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
