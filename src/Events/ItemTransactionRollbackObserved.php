<?php

namespace AlexKassel\ScraperCore\Events;

readonly class ItemTransactionRollbackObserved
{
    public function __construct(
        public string $errorMessage,
        public string $exceptionClass
    ) {}
}
