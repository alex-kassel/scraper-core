<?php

namespace AlexKassel\ScraperCore\Lifecycle;

use AlexKassel\ScraperCore\Contracts\ScraperLifecycleExtensionInterface;
use AlexKassel\ScraperCore\Contracts\LifecycleContextInterface;
use AlexKassel\ScraperCore\Contracts\StepContextInterface;
use AlexKassel\ScraperCore\Contracts\ResultContextInterface;
use AlexKassel\ScraperCore\DTOs\SpiderRunContext;
use AlexKassel\ScraperCore\DTOs\ItemDiscoveryDecision;
use AlexKassel\ScraperCore\DTOs\SpiderRunFinishedPayload;
use AlexKassel\ScraperCore\DTOs\SpiderRunFailedPayload;

abstract class AbstractScraperLifecycleExtension implements ScraperLifecycleExtensionInterface
{
    public function boot(LifecycleContextInterface $context): void {}
    public function shutdown(LifecycleContextInterface $context): void {}

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
