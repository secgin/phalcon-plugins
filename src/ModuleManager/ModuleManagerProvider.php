<?php

namespace YG\Phalcon\ModuleManager;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class ModuleManagerProvider implements ServiceProviderInterface
{
    protected string $providerName = 'moduleManager';

    public function register(DiInterface $di): void
    {
        $rootPath = $di->offsetGet('rootPath');

        $di->setShared($this->providerName, function() use ($rootPath) {
            $moduleManager = new ModuleManager();

            $modulesFile = require $rootPath . '/config/modules.php';
            if (file_exists($modulesFile) and is_readable($modulesFile))
                include $modulesFile;

            return $moduleManager;
        });
    }
}