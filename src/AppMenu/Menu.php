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

    public function renderMainMenu(array $parameters = array()): string
    {
        $this->loadMenus();

        $html = Tag::tagHtml('s-menu', $parameters);

        foreach ($this->items as $menuItem)
            $html .= $this->renderMenuItem($menuItem);

        $html .= Tag::tagHtmlClose('s-menu');

        return $html;
    }

    private function loadMenus()
    {
        foreach ($this->menus as $moduleName => $menus)
        {
            foreach ($menus as $title => $menu)
            {
                $menuItem = new MenuItem(
                    $title,
                    $menu['url'] ?? null,
                    $menu['icon'] ?? null,
                    $menu['parameters'] ?? []);

                if (isset($menu['submenus']) and is_array($menu['submenus']))
                {
                    foreach ($menu['submenus'] as $subMenuTitle => $subMenu)
                    {
                        $subMenuItem = new MenuItem(
                            $subMenuTitle,
                            $subMenu['url'] ?? null,
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


    private function renderMenuItem(MenuItem $menuItem): string
    {
        return count($menuItem->items) > 0
            ? $this->renderDropDownMenuItem($menuItem)
            : $this->renderLinkMenuItem($menuItem);
    }

    private function renderDropDownMenuItem(MenuItem $menuItem): string
    {
        $parameters = [
            'title' => $menuItem->title
        ];
        if ($menuItem->active)
            $parameters['dopen'] = '';

        $parameters = array_merge($parameters, $menuItem->parameters);

        $html = Tag::tagHtml('s-menu-dropdown', $parameters)
            . Tag::tagHtml('i', ['class' => $menuItem->icon, 'slot' => 'icon'])
            . Tag::tagHtmlClose('i');

        foreach ($menuItem->items as $subMenu)
        {
            $html .= Tag::tagHtml('s-menu-link', ['href' => $subMenu->url]);

            if ($subMenu->icon != '')
                $html .= Tag::tagHtml('i', ['class' => $subMenu->icon, 'slot' => 'icon'])
                    . Tag::tagHtmlClose('i');

            $html .= $subMenu->title
                . Tag::tagHtmlClose('s-menu-link');
        }

        $html .= Tag::tagHtmlClose('s-menu-dropdown');

        return $html;
    }

    private function renderLinkMenuItem(MenuItem $menuItem): string
    {
        $parameters = [
            'href' => $menuItem->url
        ];

        $html = Tag::tagHtml('s-menu-link', $parameters);

        if ($menuItem->icon != '')
            $html .= Tag::tagHtml('i', ['class' => $menuItem->icon, 'slot' => 'icon'])
                . Tag::tagHtmlClose('i');

        $html .= $menuItem->title
            . Tag::tagHtmlClose('s-menu-link');

        return $html;
    }
}