<?php

namespace AlexKassel\ScraperCore\Lifecycle;

use AlexKassel\ScraperCore\DTOs\SpiderResult;
use AlexKassel\ScraperCore\DTOs\ItemDiscoveryDecision;
use AlexKassel\ScraperCore\Enums\SpiderResultStatus;
use AlexKassel\ScraperCore\Enums\ItemDiscoveryClassification;
use AlexKassel\ScraperCore\Enums\ItemMissingCause;

class CoreLifecycleBundle
{
    private array $seenExternalIds = [];

    public function __construct(
        private FingerprintComparator $comparator = new FingerprintComparator()
    ) {}

    public function resetRunState(): void
    {
        $this->seenExternalIds = [];
    }

    /**
     * Process SpiderResult through the mandatory 5-step Core Lifecycle logic.
     */
    public function evaluate(SpiderResult $result, ?string $storedFingerprint = null, array $excludePaths = []): ItemDiscoveryDecision
    {
        // 1. Seen tracking
        $isDuplicate = isset($this->seenExternalIds[$result->externalId]);
        $this->seenExternalIds[$result->externalId] = true;

        // 2. Fingerprint calculation
        $currentFingerprint = $this->comparator->calculateFingerprint($result->payload, $excludePaths);

        // 3. Classification
        $classification = match ($result->status) {
            SpiderResultStatus::NewItem => ItemDiscoveryClassification::NewItem,
            SpiderResultStatus::Changed => ItemDiscoveryClassification::ActiveFingerprintDiffers,
            SpiderResultStatus::Unchanged => ItemDiscoveryClassification::ActiveUnchanged,
            SpiderResultStatus::Missing => ItemDiscoveryClassification::KnownMissingItem,
            SpiderResultStatus::Failed => ItemDiscoveryClassification::IncompleteDiscovery,
            SpiderResultStatus::Available => $this->classifyAvailable($storedFingerprint, $currentFingerprint),
        };

        $detectedChanges = [];
        if ($classification === ItemDiscoveryClassification::ActiveFingerprintDiffers) {
            $detectedChanges[] = 'payload_fingerprint_mismatch';
        }

        return new ItemDiscoveryDecision(
            result: $result,
            classification: $classification,
            calculatedFingerprint: $currentFingerprint,
            detectedChanges: $detectedChanges
        );
    }

    private function classifyAvailable(?string $storedFingerprint, ?string $currentFingerprint): ItemDiscoveryClassification
    {
        if ($storedFingerprint === null) {
            return ItemDiscoveryClassification::NewItem;
        }

        if ($this->comparator->matches($storedFingerprint, $currentFingerprint)) {
            return ItemDiscoveryClassification::ActiveUnchanged;
        }

        return ItemDiscoveryClassification::ActiveFingerprintDiffers;
    }
}
