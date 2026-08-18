<?php

namespace AlexKassel\ScraperCore\Spiders\Default;

use RoachPHP\Http\Response;
use AlexKassel\ScraperCore\Spiders\AbstractSpider;
use AlexKassel\ScraperCore\DTOs\SpiderResult;
use AlexKassel\ScraperCore\Enums\SpiderResultStatus;
use Generator;

class SampleCatalogSpider extends AbstractSpider
{
    public function getDomainSlug(): string
    {
        return 'sample-domain';
    }

    public function getSpiderSlug(): string
    {
        return 'sample-catalog-spider';
    }

    /** @var array<int, string> Initial URLs to crawl */
    public array $startUrls = [
        'https://example.com/catalog'
    ];

    /**
     * Parse response and yield SpiderResult DTO items.
     *
     * @param Response $response
     * @return Generator<int, SpiderResult, mixed, void>
     */
    public function parse(Response $response): Generator
    {
        // Example: extract catalog items from response
        $items = $response->json('data') ?? [];

        foreach ($items as $itemData) {
            yield $this->createResult(
                externalId: (string) ($itemData['id'] ?? uniqid()),
                payload: $itemData,
                status: SpiderResultStatus::Available
            );
        }
    }
}
