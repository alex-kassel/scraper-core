<?php

namespace AlexKassel\ScraperCore\Tests\Integration;

use Orchestra\Testbench\TestCase;
use Exception;
use AlexKassel\ScraperCore\Lifecycle\ItemTransactionManager;
use AlexKassel\ScraperCore\Events\NewItemObserved;
use AlexKassel\ScraperCore\Events\ItemTransactionRollbackObserved;
use AlexKassel\ScraperCore\Providers\ScraperCoreServiceProvider;
use Illuminate\Support\Facades\Event;

class ItemTransactionManagerIntegrationTest extends TestCase
{
    private ItemTransactionManager $txManager;

    protected function getPackageProviders($app): array
    {
        return [ScraperCoreServiceProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->txManager = new ItemTransactionManager();
    }

    public function test_commit_before_dispatch_dispatches_events_after_successful_transaction(): void
    {
        Event::fake([NewItemObserved::class]);

        $this->txManager->executeInTransaction(function (array &$eventsToDispatch) {
            $eventsToDispatch[] = new NewItemObserved('domain-one', 'spider-one', 'item-1', 'hash123');
            return 'success';
        });

        Event::assertDispatched(NewItemObserved::class, function ($event) {
            return $event->externalId === 'item-1' && $event->domainSlug === 'domain-one';
        });
    }

    public function test_rollback_dispatches_diagnostic_rollback_event_and_rethrows_exception(): void
    {
        Event::fake([ItemTransactionRollbackObserved::class, NewItemObserved::class]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Database error simulated");

        try {
            $this->txManager->executeInTransaction(function (array &$eventsToDispatch) {
                $eventsToDispatch[] = new NewItemObserved('domain-one', 'spider-one', 'item-1', 'hash123');
                throw new Exception("Database error simulated");
            });
        } finally {
            Event::assertNotDispatched(NewItemObserved::class);
            Event::assertDispatched(ItemTransactionRollbackObserved::class);
        }
    }
}
