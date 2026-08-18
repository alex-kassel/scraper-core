<?php

namespace AlexKassel\ScraperCore\Exceptions;

use AlexKassel\LaravelActionableDiagnostics\Enums\ErrorSeverityEnum;

class DomainConnectionNotFoundException extends ScraperCoreException
{
    public function __construct(string $domainSlug, string $connectionName, array $searchedPaths)
    {
        $message = "Database connection '{$connectionName}' for domain '{$domainSlug}' not found. Searched paths: " . implode(', ', $searchedPaths);
        parent::__construct(
            message: $message,
            severity: ErrorSeverityEnum::FATAL,
            diagnosticData: [
                'domain_slug' => $domainSlug,
                'connection_name' => $connectionName,
                'searched_paths' => $searchedPaths,
            ]
        );
    }

    public function getRemediationSteps(): array
    {
        return [
            "Ensure SQLite database file exists at one of the searched paths.",
            "Configure 'auto_create_sqlite_database = true' in scraper configuration.",
            "Define connection explicit entry in config/database.php."
        ];
    }
}
