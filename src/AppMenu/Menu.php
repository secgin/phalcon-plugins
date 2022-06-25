<?php

namespace YG\Phalcon\AppMenu;

use Phalcon\Di\Injectable;

class Menu extends Injectable implements MenuInterface
{
    private array $menus = [];
    private array $sortedMenus = [];

    /** @var array|MenuItem[] */
    private $items = [];

    public function assign(array $menu): void
    {
        array_push($this->menus, $menu);
    }

    private function loadMenus()
    {
        $items = [];

        foreach ($this->menus as $moduleName => $menus)
        {
            foreach ($menus as $title => $menu)
            {
                $menuItem = new MenuItem(
                    $title,
                    $this->url->get($menu['url'] ?? null),
                    $menu['icon'] ?? null,
                    $menu['parameters'] ?? []);

                if (isset($menu['submenus']) and is_array($menu['submenus']))
                {
                    foreach ($menu['submenus'] as $subMenuTitle => $subMenu)
                    {
                        $subMenuItem = new MenuItem(
                            $subMenuTitle,
                            $this->url->get($subMenu['url'] ?? null),
                            $subMenu['icon'] ?? null,
                            $subMenu['parameters'] ?? []
                        );

                        if ($this->router->getMatchedRoute() != null)
                        {
                            $matchedRoutePattern = $this->router->getMatchedRoute()->getPattern();

                            if ($subMenuItem->url == $matchedRoutePattern)
                                $menuItem->active = true;
                        }

                        $menuItem->addSubMenu($subMenuItem);
                    }

                }

                if (array_key_exists($menuItem->title, $items))
                {
                    foreach ($menuItem->items as $item)
                        $items[$menuItem->title]->addSubMenu($item);
                }
                else
                    $items[$menuItem->title] = $menuItem;
            }
        }

        $newItems = [];
        foreach ($this->sortedMenus as $item)
        {
            $title = $item['title'];
            $icon = $item['icon'] ?? '';

            if (array_key_exists($title, $items))
            {
                if (array_key_exists($title, $newItems))
                {
                    foreach ($items[$title]->items as $subItem)
                        $newItems[$title]->addSubMenu($subItem);
                }
                else
                {
                    $newItems[$title] = $items[$title];
                    $newItems[$title]->icon = $icon;
                }

                unset($items[$title]);
            }
        }

        foreach ($items as $tile => $item)
            $newItems[$tile] = $item;

        $this->items = $newItems;
    }

    public function getMenus(): array
    {
        $this->loadMenus();
        return $this->items;
    }

    public function setMenuSort(array $sortedMenus): void
    {
        $this->sortedMenus = $sortedMenus;
    }
}