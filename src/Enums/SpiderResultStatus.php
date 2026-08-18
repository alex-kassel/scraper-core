<?php

namespace AlexKassel\ScraperCore\Enums;

enum SpiderResultStatus: string
{
    case NewItem = 'new_item';
    case Changed = 'changed';
    case Unchanged = 'unchanged';
    case Available = 'available';
    case Missing = 'missing';
    case Failed = 'failed';

    public function isSuccess(): bool
    {
        return $this !== self::Failed;
    }
}
