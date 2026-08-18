<?php

namespace AlexKassel\ScraperCore\Events;

readonly class SpiderRunSkippedDueToOverlap
{
    public function __construct(
        public string $domainSlug,
        public string $spiderSlug,
        public string $lockKey
    ) {}
}
