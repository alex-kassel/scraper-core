<?php

namespace AlexKassel\ScraperCore\DTOs;

readonly class DomainRunCounters
{
    public function __construct(
        public int $spidersExecuted = 0,
        public int $itemsDiscovered = 0,
        public int $itemsNew = 0,
        public int $itemsChanged = 0,
        public int $itemsUnchanged = 0,
        public int $itemsMissing = 0,
        public int $itemsReappeared = 0,
        public int $itemsFailed = 0
    ) {}

    public function addSpiderRun(SpiderRunCounters $counters): self
    {
        return new self(
            $this->spidersExecuted + 1,
            $this->itemsDiscovered + $counters->itemsDiscovered,
            $this->itemsNew + $counters->itemsNew,
            $this->itemsChanged + $counters->itemsChanged,
            $this->itemsUnchanged + $counters->itemsUnchanged,
            $this->itemsMissing + $counters->itemsMissing,
            $this->itemsReappeared + $counters->itemsReappeared,
            $this->itemsFailed + $counters->itemsFailed
        );
    }
}
