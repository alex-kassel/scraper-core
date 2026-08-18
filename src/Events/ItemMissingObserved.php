<?php

namespace AlexKassel\ScraperCore\Events;

use AlexKassel\ScraperCore\Enums\ItemMissingCause;

readonly class ItemMissingObserved
{
    public function __construct(
        public string $domainSlug,
        public string $spiderSlug,
        public string $externalId,
        public ItemMissingCause $cause
    ) {}
}
