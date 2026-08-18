<?php

namespace AlexKassel\ScraperCore\Tests\Integration;

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Schema;
use AlexKassel\ScraperCore\Providers\ScraperCoreServiceProvider;

class DatabaseMigrationsTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [ScraperCoreServiceProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }

    public function test_scraper_core_database_tables_are_created_successfully(): void
    {
        $this->assertTrue(Schema::hasTable('scraper_items'));
        $this->assertTrue(Schema::hasTable('scraper_item_spider_metadata'));
        $this->assertTrue(Schema::hasTable('scraper_spider_runs'));
        $this->assertTrue(Schema::hasTable('scraper_domain_runs'));
        $this->assertTrue(Schema::hasTable('scraper_item_changes'));
        $this->assertTrue(Schema::hasTable('scraper_item_missing_periods'));
    }
}
