<?php

namespace AlexKassel\ScraperCore\DTOs;

use AlexKassel\PlatformContracts\ResultContextInterface;

readonly class SpiderRunFinishedPayload implements ResultContextInterface
{
    public function __construct(
        public SpiderRunContext $context,
        public SpiderRunCounters $counters,
        public float $durationSeconds
    ) {}
}
