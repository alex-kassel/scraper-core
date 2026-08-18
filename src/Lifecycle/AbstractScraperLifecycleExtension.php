<?php

namespace AlexKassel\ScraperCore\Lifecycle;

use AlexKassel\PlatformContracts\AbstractLifecycleExtension;
use AlexKassel\PlatformContracts\LifecycleContextInterface;
use AlexKassel\PlatformContracts\StepContextInterface;
use AlexKassel\PlatformContracts\ResultContextInterface;
use AlexKassel\ScraperCore\DTOs\SpiderRunContext;
use AlexKassel\ScraperCore\DTOs\ItemDiscoveryDecision;
use AlexKassel\ScraperCore\DTOs\SpiderRunFinishedPayload;
use AlexKassel\ScraperCore\DTOs\SpiderRunFailedPayload;

abstract class AbstractScraperLifecycleExtension extends AbstractLifecycleExtension
{
    final public function beforeProcess(LifecycleContextInterface $context): void
    {
        if ($context instanceof SpiderRunContext) {
            $this->onSpiderRunStarting($context);
        }
    }

    final public function onStep(StepContextInterface $context): void
    {
        if ($context instanceof ItemDiscoveryDecision) {
            $context->isDropped()
                ? $this->onItemDropped($context)
                : $this->onItemProcessed($context);
        }
    }

    final public function afterProcess(ResultContextInterface $context): void
    {
        if ($context instanceof SpiderRunFinishedPayload) {
            $this->onSpiderRunFinished($context);
        } elseif ($context instanceof SpiderRunFailedPayload) {
            $this->onSpiderRunFailed($context);
        }
    }

    // Domain hooks for specialized spider extension classes:
    public function onSpiderRunStarting(SpiderRunContext $context): void {}
    public function onItemProcessed(ItemDiscoveryDecision $decision): void {}
    public function onItemDropped(ItemDiscoveryDecision $decision): void {}
    public function onSpiderRunFinished(SpiderRunFinishedPayload $payload): void {}
    public function onSpiderRunFailed(SpiderRunFailedPayload $payload): void {}
}
