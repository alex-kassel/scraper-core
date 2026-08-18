<?php

namespace AlexKassel\ScraperCore\Enums;

enum ErrorSeverity: string
{
    case Fatal = 'fatal';
    case Recoverable = 'recoverable';
    case Operational = 'operational';
}
