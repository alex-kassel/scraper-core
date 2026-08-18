<?php

namespace AlexKassel\ScraperCore\DTOs;

use Throwable;
use AlexKassel\ScraperCore\Contracts\ResultContextInterface;

readonly class SpiderRunFailedPayload implements ResultContextInterface
{
    public function __construct(
        public SpiderRunContext $context,
        public Throwable $exception,
        public SpiderRunCounters $counters
    ) {}
}
