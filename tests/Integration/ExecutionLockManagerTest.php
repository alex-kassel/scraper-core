<?php

namespace AlexKassel\ScraperCore\Tests\Integration;

use Orchestra\Testbench\TestCase;
use AlexKassel\ScraperCore\Services\ExecutionLockManager;
use AlexKassel\ScraperCore\Exceptions\LockAcquisitionFailedException;
use AlexKassel\ScraperCore\Providers\ScraperCoreServiceProvider;

class ExecutionLockManagerTest extends TestCase
{
    private ExecutionLockManager $lockManager;

    protected function getPackageProviders($app): array
    {
        return [ScraperCoreServiceProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->lockManager = new ExecutionLockManager();
    }

    public function test_acquire_lock_succeeds_for_first_call(): void
    {
        $lock = $this->lockManager->acquire('domain-one', 'spider-one', 60);
        $this->assertNotNull($lock);

        $this->lockManager->release('domain-one', 'spider-one');
    }

    public function test_acquire_lock_throws_exception_when_lock_already_held(): void
    {
        $this->lockManager->acquire('domain-one', 'spider-two', 60);

        $this->expectException(LockAcquisitionFailedException::class);
        $this->lockManager->acquire('domain-one', 'spider-two', 60);
    }

    public function test_force_clear_releases_lock(): void
    {
        $this->lockManager->acquire('domain-one', 'spider-three', 60);
        $this->lockManager->forceClear('domain-one', 'spider-three');

        $lock2 = $this->lockManager->acquire('domain-one', 'spider-three', 60);
        $this->assertNotNull($lock2);
        $this->lockManager->release('domain-one', 'spider-three');
    }
}
