<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Spiders Directory & Namespace
    |--------------------------------------------------------------------------
    |
    | Specifies where custom source Spiders reside by default in your Laravel
    | application or specialized scraping package.
    |
    | Default directory: app/Spiders/
    | Default namespace: App\Spiders\
    |
    */

    'spiders_path' => app_path('Spiders'),

    'spiders_namespace' => 'App\\Spiders',

    /*
    |--------------------------------------------------------------------------
    | Database Connection & Persistence
    |--------------------------------------------------------------------------
    |
    | Database connection name to use for scraping tables. When null, uses the
    | active DomainContext connection or default application connection.
    |
    */

    'connection' => env('SCRAPER_DB_CONNECTION', null),

    'auto_create_sqlite_database' => env('SCRAPER_AUTO_CREATE_SQLITE', false),

    /*
    |--------------------------------------------------------------------------
    | Batch Watermark Buffer Threshold
    |--------------------------------------------------------------------------
    |
    | Maximum number of unchanged item observations buffered in memory before
    | executing a single bulk database update flush.
    |
    */

    'unchanged_batch_threshold' => 500,

    /*
    |--------------------------------------------------------------------------
    | Execution Lock Settings
    |--------------------------------------------------------------------------
    |
    | Default TTL (in seconds) for atomic overlapping-run execution locks.
    |
    */

    'lock_ttl_seconds' => 3600,
];
