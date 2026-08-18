<?php

namespace AlexKassel\ScraperCore\Events;

use AlexKassel\ScraperCore\DTOs\DomainRunCounters;

readonly class DomainRunFinished
{
    public function __construct(
        public string $domainSlug,
        public int $domainRunId,
        public DomainRunCounters $counters,
        public float $durationSeconds
    ) {}
}
