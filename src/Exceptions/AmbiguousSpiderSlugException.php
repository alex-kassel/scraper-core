<?php

namespace AlexKassel\ScraperCore\Exceptions;

use AlexKassel\LaravelActionableDiagnostics\Enums\ErrorSeverityEnum;

class AmbiguousSpiderSlugException extends ScraperCoreException
{
    public function __construct(string $spiderSlug, array $matchingClasses)
    {
        $message = "Ambiguous spider slug '{$spiderSlug}' matches multiple registered classes: " . implode(', ', $matchingClasses);
        parent::__construct(
            message: $message,
            severity: ErrorSeverityEnum::FATAL,
            diagnosticData: [
                'spider_slug' => $spiderSlug,
                'matching_classes' => $matchingClasses,
            ]
        );
    }

    public function getRemediationSteps(): array
    {
        return [
            "Use fully qualified class names or domain-prefixed slugs when invoking spiders.",
            "Ensure spider slugs are unique across registered domains."
        ];
    }
}
