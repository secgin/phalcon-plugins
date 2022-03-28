<?php
namespace YG\Phalcon\AppMenu;

interface MenuInterface
{
    public function assign(array $menu): void;

    public function renderMainMenu(array $parameters = array()): string;
}