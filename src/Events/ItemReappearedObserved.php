<?php

namespace AlexKassel\ScraperCore\Events;

readonly class ItemReappearedObserved
{
    public function __construct(
        public string $domainSlug,
        public string $spiderSlug,
        public string $externalId,
        public ?string $fingerprint
    ) {}
}
