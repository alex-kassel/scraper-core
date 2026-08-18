<?php

namespace AlexKassel\ScraperCore\Exceptions;

use AlexKassel\LaravelActionableDiagnostics\Enums\ErrorSeverityEnum;

class ContractViolationException extends ScraperCoreException
{
    public function __construct(string $message, array $context = [])
    {
        parent::__construct(
            message: $message,
            severity: ErrorSeverityEnum::FATAL,
            diagnosticData: $context
        );
    }

    public function getRemediationSteps(): array
    {
        return [
            "Review Spider item processor output DTO structure.",
            "Ensure SpiderResult status and cause combinations satisfy Core contract rules."
        ];
    }
}
