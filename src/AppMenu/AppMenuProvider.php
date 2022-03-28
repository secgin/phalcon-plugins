<?php

namespace YG\Plugins\AppMenu;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class AppMenuProvider implements ServiceProviderInterface
{
    protected string $providerName = 'appMenu';

    public function register(DiInterface $di): void
    {
        $rootPath = $di->offsetGet('rootPath');

        $di->setShared($this->providerName, function() use ($rootPath) {
            $menu = new Menu();

            $menusFile = require $rootPath . '/config/menus.php';
            if (file_exists($menusFile) and is_readable($menusFile))
                include $menusFile;

            return $menu;
        });
    }
}