<?php

namespace AlexKassel\ScraperCore\Contracts;

interface ScraperLifecycleExtensionInterface
{
    public function boot(LifecycleContextInterface $context): void;
    public function beforeProcess(LifecycleContextInterface $context): void;
    public function onStep(StepContextInterface $context): void;
    public function afterProcess(ResultContextInterface $context): void;
    public function shutdown(LifecycleContextInterface $context): void;
}
