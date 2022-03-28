<?php

namespace YG\Plugins\AppMenu;

class MenuItem
{
    public string $title;

    public ?string $url;

    public ?string $icon;

    public array $parameters;

    /**
     * @var array|MenuItem[]
     */
    public array $items;

    public bool $active;

    public function __construct(string $title, ?string $url, ?string $icon, array $parameters = [])
    {
        $this->title = $title;
        $this->url = $url;
        $this->icon = $icon;
        $this->parameters = $parameters;
        $this->active = false;
        $this->items = [];
    }

    public function addSubMenu(MenuItem $menuItem)
    {
        array_push($this->items, $menuItem);
    }
}