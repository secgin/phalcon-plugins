<?php

namespace YG\Phalcon\ModuleManager;

interface ModuleManagerInterface
{
    public function setModules(array $value): void;

    public function getModules(): array;

    public function getDefaultModuleName(): string;

    public function getDefaultModuleNamespace(): string;
}