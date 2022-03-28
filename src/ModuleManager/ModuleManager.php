<?php

namespace YG\Phalcon\ModuleManager;

class ModuleManager implements ModuleManagerInterface
{
    private array $modules = [];

    private string $defaultModuleName = '';

    private string $defaultModuleNamespace = '';


    public function setModules(array $value): void
    {
        $this->modules = $value;
        $this->loadDefaultModule();
    }

    public function getModules(): array
    {
        return $this->modules;
    }

    public function getDefaultModuleName(): string
    {
        return $this->defaultModuleName;
    }

    public function getDefaultModuleNamespace(): string
    {
        return $this->defaultModuleNamespace;
    }

    private function loadDefaultModule(): void
    {
        foreach ($this->modules as $moduleName => $module)
        {
            if (isset($module['default']))
            {
                $this->defaultModuleName = $moduleName;
                $this->defaultModuleNamespace = substr($module['className'], 0, strlen($module['className']) - 7);
                break;
            }
        }
    }
}