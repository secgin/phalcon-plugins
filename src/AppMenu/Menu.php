<?php

namespace YG\Phalcon\AppMenu;

use Phalcon\Di\Injectable;
use Phalcon\Tag;

class Menu extends Injectable implements MenuInterface
{
    private array $menus = [];

    /** @var array|MenuItem[] */
    private $items = [];

    public function assign(array $menu): void
    {
        array_push($this->menus, $menu);
    }

    private function loadMenus()
    {
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

                if (array_key_exists($menuItem->title, $this->items))
                {
                    if ($this->items[$menuItem->title]->icon == '')
                        $this->items[$menuItem->title]->icon = $menuItem->icon;

                    foreach ($menuItem->items as $item)
                        $this->items[$menuItem->title]->addSubMenu($item);
                }
                else
                    $this->items[$menuItem->title] = $menuItem;
            }
        }
    }

    public function getMenus(): array
    {
        $this->loadMenus();
        return $this->items;
    }
}