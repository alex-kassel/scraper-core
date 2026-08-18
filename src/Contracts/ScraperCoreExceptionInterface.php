<?php

namespace AlexKassel\ScraperCore\Contracts;

use AlexKassel\LaravelActionableDiagnostics\Contracts\ActionableExceptionInterface;
use AlexKassel\LaravelActionableDiagnostics\Enums\ErrorSeverityEnum;

interface ScraperCoreExceptionInterface extends ActionableExceptionInterface
{
    public function getSeverity(): ErrorSeverityEnum;
}
