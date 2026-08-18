<?php

namespace AlexKassel\ScraperCore\Enums;

enum PipelineMode: string
{
    case Normal = 'normal';
    case DryRun = 'dry_run';
    case ForceRefresh = 'force_refresh';
}
