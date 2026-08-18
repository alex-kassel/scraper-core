<?php

namespace AlexKassel\ScraperCore\Enums;

enum ItemDiscoveryClassification: string
{
    case NewItem = 'new_item';
    case ActiveUnchanged = 'active_unchanged';
    case ActiveFingerprintDiffers = 'active_fingerprint_differs';
    case KnownMissingItem = 'known_missing_item';
    case IncompleteDiscovery = 'incomplete_discovery';
}
