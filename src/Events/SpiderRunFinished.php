<?php

namespace AlexKassel\ScraperCore\Events;

use AlexKassel\ScraperCore\DTOs\SpiderRunCounters;

readonly class SpiderRunFinished
{
    public function __construct(
        public string $domainSlug,
        public string $spiderSlug,
        public int $spiderRunId,
        public SpiderRunCounters $counters,
        public float $durationSeconds
    ) {}
}
