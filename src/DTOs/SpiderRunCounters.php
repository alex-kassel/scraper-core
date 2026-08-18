<?php

namespace AlexKassel\ScraperCore\DTOs;

readonly class SpiderRunCounters
{
    public function __construct(
        public int $itemsDiscovered = 0,
        public int $itemsNew = 0,
        public int $itemsChanged = 0,
        public int $itemsUnchanged = 0,
        public int $itemsMissing = 0,
        public int $itemsReappeared = 0,
        public int $itemsFailed = 0
    ) {}

    public function incrementNew(): self
    {
        return new self(
            $this->itemsDiscovered + 1,
            $this->itemsNew + 1,
            $this->itemsChanged,
            $this->itemsUnchanged,
            $this->itemsMissing,
            $this->itemsReappeared,
            $this->itemsFailed
        );
    }

    public function incrementChanged(): self
    {
        return new self(
            $this->itemsDiscovered + 1,
            $this->itemsNew,
            $this->itemsChanged + 1,
            $this->itemsUnchanged,
            $this->itemsMissing,
            $this->itemsReappeared,
            $this->itemsFailed
        );
    }

    public function incrementUnchanged(): self
    {
        return new self(
            $this->itemsDiscovered + 1,
            $this->itemsNew,
            $this->itemsChanged,
            $this->itemsUnchanged + 1,
            $this->itemsMissing,
            $this->itemsReappeared,
            $this->itemsFailed
        );
    }

    public function incrementMissing(): self
    {
        return new self(
            $this->itemsDiscovered,
            $this->itemsNew,
            $this->itemsChanged,
            $this->itemsUnchanged,
            $this->itemsMissing + 1,
            $this->itemsReappeared,
            $this->itemsFailed
        );
    }

    public function incrementReappeared(): self
    {
        return new self(
            $this->itemsDiscovered + 1,
            $this->itemsNew,
            $this->itemsChanged,
            $this->itemsUnchanged,
            $this->itemsMissing,
            $this->itemsReappeared + 1,
            $this->itemsFailed
        );
    }

    public function incrementFailed(): self
    {
        return new self(
            $this->itemsDiscovered + 1,
            $this->itemsNew,
            $this->itemsChanged,
            $this->itemsUnchanged,
            $this->itemsMissing,
            $this->itemsReappeared,
            $this->itemsFailed + 1
        );
    }
}
