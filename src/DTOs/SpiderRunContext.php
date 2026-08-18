<?php

namespace AlexKassel\ScraperCore\DTOs;

use AlexKassel\PlatformContracts\LifecycleContextInterface;
use AlexKassel\ScraperCore\Enums\PipelineMode;

readonly class SpiderRunContext implements LifecycleContextInterface
{
    public function __construct(
        public string $domainSlug,
        public string $spiderSlug,
        public int $spiderRunId,
        public int $domainRunId,
        public PipelineMode $mode = PipelineMode::Normal
    ) {}
}
