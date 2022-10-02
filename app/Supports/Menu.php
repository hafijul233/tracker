<?php


namespace App\Supports;


use Illuminate\Support\Facades\Config;

class Menu
{
    private $items = [];

    private $start = '<li class="nav-item {customClass}">';

    private $end = '</li>';

    private $parentExpendClass = 'menu-open';

    private $childWrapperStart = '<ul class="nav nav-treeview {childWrapperCustomClass}">';

    private $childWrapperEnd = '</ul>';

    public function __construct($menuItems = [])
    {
        $this->items = (empty($menuItems))
            ? Config::get('trucker.menu')
            : $menuItems;
    }

    public static function render()
    {

    }


}