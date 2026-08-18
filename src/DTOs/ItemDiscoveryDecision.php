<?php

namespace AlexKassel\ScraperCore\DTOs;

use AlexKassel\ScraperCore\Contracts\StepContextInterface;
use AlexKassel\ScraperCore\Enums\ItemDiscoveryClassification;

class ItemDiscoveryDecision implements StepContextInterface
{
    private bool $dropped = false;

    public function __construct(
        public readonly SpiderResult $result,
        public readonly ItemDiscoveryClassification $classification,
        public readonly ?string $calculatedFingerprint = null,
        public readonly array $detectedChanges = []
    ) {}

    public function drop(): void
    {
        $this->dropped = true;
    }

    public function isDropped(): bool
    {
        return $this->dropped;
    }
}
