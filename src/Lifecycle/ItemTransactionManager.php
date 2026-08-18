<?php

namespace AlexKassel\ScraperCore\Lifecycle;

use Closure;
use Throwable;
use Illuminate\Support\Facades\DB;
use AlexKassel\ScraperCore\Events\ItemTransactionRollbackObserved;

class ItemTransactionManager
{
    /**
     * Execute callback inside database transaction.
     * Guarantees Commit-Before-Dispatch by returning events to be dispatched strictly after transaction commits.
     */
    public function executeInTransaction(Closure $operation, ?Closure $rollbackCallback = null): mixed
    {
        $eventsToDispatch = [];

        try {
            $result = DB::transaction(function () use ($operation, &$eventsToDispatch) {
                return $operation($eventsToDispatch);
            });

            // Commit-Before-Dispatch Guarantee: Dispatch events after DB transaction succeeds
            foreach ($eventsToDispatch as $event) {
                if (function_exists('event')) {
                    event($event);
                }
            }

            return $result;
        } catch (Throwable $e) {
            if ($rollbackCallback !== null) {
                $rollbackCallback($e);
            }

            if (function_exists('event')) {
                event(new ItemTransactionRollbackObserved($e->getMessage(), get_class($e)));
            }

            throw $e;
        }
    }
}
