<?php

namespace AlexKassel\ScraperCore\DTOs;

use AlexKassel\ScraperCore\Contracts\ResultContextInterface;

readonly class SpiderRunFinishedPayload implements ResultContextInterface
{
    public function __construct(
        public SpiderRunContext $context,
        public SpiderRunCounters $counters,
        public float $durationSeconds
    ) {}
}
