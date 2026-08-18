<?php

namespace AlexKassel\ScraperCore\Enums;

enum ItemMissingCause: string
{
    case ResultDrivenExplicit404 = 'result_driven_explicit_404';
    case ResultDrivenSpiderDeclaredMissing = 'result_driven_spider_declared_missing';
    case CoreOwnedAbsenceAfterCompleteDiscovery = 'core_owned_absence_after_complete_discovery';
    case Unknown = 'unknown';
}
