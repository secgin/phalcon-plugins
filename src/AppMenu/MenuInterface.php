<?php
namespace YG\Phalcon\AppMenu;

interface MenuInterface
{
    public function assign(array $menu): void;

    /**
     * @return array|MenuItem[]
     */
    public function getMenus(): array;

    public function setMenuSort(array $sortedMenus): void;
}