<?php

namespace AlexKassel\ScraperCore\Providers;

use Illuminate\Support\ServiceProvider;
use AlexKassel\ScraperCore\Services\ExecutionLockManager;
use AlexKassel\ScraperCore\Lifecycle\CoreLifecycleBundle;
use AlexKassel\ScraperCore\Lifecycle\FingerprintComparator;
use AlexKassel\ScraperCore\Lifecycle\ItemTransactionManager;
use AlexKassel\ScraperCore\Lifecycle\UnchangedBatchBuffer;

class ScraperCoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/scraper-core.php', 'scraper-core');

        $this->app->singleton(ExecutionLockManager::class);
        $this->app->singleton(FingerprintComparator::class);
        $this->app->singleton(CoreLifecycleBundle::class);
        $this->app->singleton(ItemTransactionManager::class);
        $this->app->singleton(UnchangedBatchBuffer::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/scraper-core.php' => config_path('scraper-core.php'),
            ], 'scraper-core-config');

            $this->publishes([
                __DIR__ . '/../../database/migrations' => database_path('migrations'),
            ], 'scraper-core-migrations');
        }
    }
}
