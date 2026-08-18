<?php

namespace AlexKassel\ScraperCore\Spiders;

use RoachPHP\Spider\BasicSpider;
use AlexKassel\ScraperCore\DTOs\SpiderResult;
use AlexKassel\ScraperCore\Enums\SpiderResultStatus;

abstract class AbstractSpider extends BasicSpider
{
    /**
     * Get domain slug associated with this spider.
     */
    abstract public function getDomainSlug(): string;

    /**
     * Get unique spider slug.
     */
    abstract public function getSpiderSlug(): string;

    /**
     * Helper to wrap extracted item into typed SpiderResult DTO.
     */
    protected function createResult(
        string $externalId,
        mixed $payload,
        SpiderResultStatus $status = SpiderResultStatus::Available,
        ?string $contentType = 'application/json'
    ): SpiderResult {
        return new SpiderResult(
            externalId: $externalId,
            status: $status,
            payload: is_array($payload) ? $payload : ['data' => $payload],
            contentType: $contentType
        );
    }
}
