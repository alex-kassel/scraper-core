<?php

namespace AlexKassel\ScraperCore\Exceptions;

use RuntimeException;
use Throwable;
use AlexKassel\ScraperCore\Contracts\ScraperCoreExceptionInterface;
use AlexKassel\LaravelActionableDiagnostics\Enums\ErrorSeverityEnum;

class ScraperCoreException extends RuntimeException implements ScraperCoreExceptionInterface
{
    public function __construct(
        string $message = "",
        protected ErrorSeverityEnum $severity = ErrorSeverityEnum::RECOVERABLE,
        protected array $diagnosticData = [],
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getDiagnosticCode(): string
    {
        return 'SCRAPER_' . strtoupper(basename(str_replace('\\', '/', get_class($this))));
    }

    public function getSeverity(): ErrorSeverityEnum
    {
        return $this->severity;
    }

    public function getRemediationSteps(): array
    {
        return [
            "Check system diagnostic logs for trace information.",
            "Verify configuration parameters and environment setup."
        ];
    }

    public function getAgentInstructions(): string
    {
        return "Review actionable diagnostics and verify spider and core state invariants.";
    }

    public function getDiagnosticContext(): array
    {
        return $this->diagnosticData;
    }
}
