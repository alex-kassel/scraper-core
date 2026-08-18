<?php

namespace AlexKassel\ScraperCore\Events;

readonly class ItemReappearedAndChangedObserved
{
    public function __construct(
        public string $domainSlug,
        public string $spiderSlug,
        public string $externalId,
        public ?string $oldFingerprint,
        public ?string $newFingerprint,
        public array $detectedChanges = []
    ) {}
}
