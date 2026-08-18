<?php

namespace AlexKassel\ScraperCore\Tests\Unit;

use PHPUnit\Framework\TestCase;
use AlexKassel\ScraperCore\Enums\SpiderResultStatus;
use AlexKassel\ScraperCore\Enums\ItemDiscoveryClassification;
use AlexKassel\ScraperCore\Enums\ItemMissingCause;
use AlexKassel\ScraperCore\DTOs\SpiderResult;
use AlexKassel\ScraperCore\DTOs\SpiderRunCounters;
use AlexKassel\ScraperCore\DTOs\DomainRunCounters;
use AlexKassel\ScraperCore\Exceptions\ContractViolationException;

class EnumsAndDTOsTest extends TestCase
{
    public function test_spider_result_status_is_success(): void
    {
        $this->assertTrue(SpiderResultStatus::NewItem->isSuccess());
        $this->assertTrue(SpiderResultStatus::Changed->isSuccess());
        $this->assertTrue(SpiderResultStatus::Unchanged->isSuccess());
        $this->assertFalse(SpiderResultStatus::Failed->isSuccess());
    }

    public function test_spider_result_throws_exception_on_empty_external_id(): void
    {
        $this->expectException(ContractViolationException::class);
        new SpiderResult('', SpiderResultStatus::NewItem);
    }

    public function test_spider_result_throws_exception_when_content_type_provided_without_payload(): void
    {
        $this->expectException(ContractViolationException::class);
        new SpiderResult('item-1', SpiderResultStatus::NewItem, payload: null, contentType: 'application/json');
    }

    public function test_spider_result_throws_exception_when_missing_cause_omitted_for_missing_status(): void
    {
        $this->expectException(ContractViolationException::class);
        new SpiderResult('item-1', SpiderResultStatus::Missing);
    }

    public function test_spider_run_counters_immutable_increments(): void
    {
        $counters = new SpiderRunCounters();
        $this->assertEquals(0, $counters->itemsDiscovered);

        $c1 = $counters->incrementNew();
        $this->assertEquals(1, $c1->itemsNew);
        $this->assertEquals(1, $c1->itemsDiscovered);

        $c2 = $c1->incrementChanged();
        $this->assertEquals(1, $c2->itemsNew);
        $this->assertEquals(1, $c2->itemsChanged);
        $this->assertEquals(2, $c2->itemsDiscovered);
    }

    public function test_domain_run_counters_aggregation(): void
    {
        $spiderCounters = (new SpiderRunCounters())->incrementNew()->incrementChanged();
        $domainCounters = (new DomainRunCounters())->addSpiderRun($spiderCounters);

        $this->assertEquals(1, $domainCounters->spidersExecuted);
        $this->assertEquals(2, $domainCounters->itemsDiscovered);
        $this->assertEquals(1, $domainCounters->itemsNew);
        $this->assertEquals(1, $domainCounters->itemsChanged);
    }
}
