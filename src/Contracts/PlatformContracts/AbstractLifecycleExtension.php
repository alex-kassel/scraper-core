<?php

namespace AlexKassel\PlatformContracts;

abstract class AbstractLifecycleExtension implements LifecycleExtensionInterface
{
    public function boot(LifecycleContextInterface $context): void {}
    public function beforeProcess(LifecycleContextInterface $context): void {}
    public function onStep(StepContextInterface $context): void {}
    public function afterProcess(ResultContextInterface $context): void {}
    public function shutdown(LifecycleContextInterface $context): void {}
}
