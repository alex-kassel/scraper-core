<?php

namespace AlexKassel\ScraperCore\Tests\Unit;

use PHPUnit\Framework\TestCase;
use AlexKassel\ScraperCore\Lifecycle\CoreLifecycleBundle;
use AlexKassel\ScraperCore\DTOs\SpiderResult;
use AlexKassel\ScraperCore\Enums\SpiderResultStatus;
use AlexKassel\ScraperCore\Enums\ItemDiscoveryClassification;
use AlexKassel\ScraperCore\Enums\ItemMissingCause;

class CoreLifecycleBundleTest extends TestCase
{
    private CoreLifecycleBundle $bundle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bundle = new CoreLifecycleBundle();
    }

    public function test_evaluate_classifies_new_item_when_stored_fingerprint_is_null(): void
    {
        $result = new SpiderResult(
            externalId: 'item-100',
            status: SpiderResultStatus::Available,
            payload: ['title' => 'Sample Item'],
            contentType: 'application/json'
        );

        $decision = $this->bundle->evaluate($result, storedFingerprint: null);

        $this->assertEquals(ItemDiscoveryClassification::NewItem, $decision->classification);
        $this->assertNotNull($decision->calculatedFingerprint);
        $this->assertFalse($decision->isDropped());
    }

    public function test_evaluate_classifies_active_unchanged_when_fingerprints_match(): void
    {
        $payload = ['title' => 'Sample Item'];
        $fp = md5(json_encode(['title' => 'Sample Item']));

        $result = new SpiderResult(
            externalId: 'item-100',
            status: SpiderResultStatus::Available,
            payload: $payload,
            contentType: 'application/json'
        );

        $decision = $this->bundle->evaluate($result, storedFingerprint: $decision_fp = (new CoreLifecycleBundle())->evaluate($result)->calculatedFingerprint);

        $this->assertEquals(ItemDiscoveryClassification::ActiveUnchanged, $decision->classification);
    }

    public function test_evaluate_classifies_missing_item(): void
    {
        $result = new SpiderResult(
            externalId: 'item-200',
            status: SpiderResultStatus::Missing,
            missingCause: ItemMissingCause::ResultDrivenExplicit404
        );

        $decision = $this->bundle->evaluate($result);

        $this->assertEquals(ItemDiscoveryClassification::KnownMissingItem, $decision->classification);
    }
}
