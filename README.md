# Scraper Core (`alex-kassel/scraper-core`)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alex-kassel/scraper-core.svg)](https://packagist.org/packages/alex-kassel/scraper-core)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)

Enterprise scraping engine for Laravel providing Roach PHP integration, atomic item discovery lifecycle management, stable fingerprint comparison, commit-before-dispatch event intents, batch watermark persistence buffers, and overlapping run lock management.

## Features

- **Roach PHP Integration:** Core Lifecycle Bundle extending Roach Spider execution.
- **Atomic 5-Step Lifecycle Engine:** Identification, fingerprint comparison, diffing, persistence, and event intent generation.
- **Stable Fingerprint Integration:** Canonicalized MD5 content hashing via `alex-kassel/stable-fingerprint`.
- **Commit-Before-Dispatch Guarantee:** Domain event intents dispatched to Laravel Event Dispatcher strictly after database transaction commit.
- **Watermark Persistence Buffer:** 500-item bulk buffer threshold for unchanged item timestamp updates (`UnchangedBatchBuffer`).
- **Single-Item Transaction Isolation:** Fail-closed per-item `DB::transaction(...)` boundaries with diagnostic rollback alerts (`ItemTransactionRollbackObserved`).
- **Overlap Lock Management:** Atomic `Cache::lock()` execution locks (`scraper:lock:{domain}:{spider}`).
- **Artisan Console Commands:** `scraper:run`, `spider:run`, `domain:run`, `scraper:preflight`, `scraper:status`.

## Installation

```bash
composer require alex-kassel/scraper-core
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
