<?php

namespace Workspace;

use TCG\Voyager\Events\MenuDisplay;
use \TCG\Voyager\Models\Menu as VoyagerMenu;

class Menu extends VoyagerMenu
{
    public static function display($menuName, $type = null, array $options = [])
    {
        // GET THE MENU - sort collection in blade

        $menu = static::where('name', '=', $menuName)
            ->with(['parent_items.children' => function ($q) {
                $q->orderBy('order');
            }])
            ->first();

        // $menu = \Cache::remember('voyager_menu_'.$menuName, \Carbon\Carbon::now()->addDays(30), function () use ($menuName) {
        //     return static::where('name', '=', $menuName)
        //     ->with(['parent_items.children' => function ($q) {
        //         $q->orderBy('order');
        //     }])
        //     ->first();
        // });
 
        // Check for Menu Existence
        if (!isset($menu)) {
            return false;
        }

        event(new MenuDisplay($menu));

        // Convert options array into object
        $options = (object) $options;

        $items = $menu->parent_items->sortBy('order');

        if ($menuName == 'admin' && $type == '_json') {
            $items = static::processItems($items);
        }

        if ($type == 'admin') {
            $type = 'voyager::menu.'.$type;
        } else {
            if (is_null($type)) {
                $type = 'voyager::menu.default';
            } elseif ($type == 'bootstrap' && !view()->exists($type)) {
                $type = 'voyager::menu.bootstrap';
            }
        }

        if (!isset($options->locale)) {
            $options->locale = app()->getLocale();
        }

        if ($type === '_json') {
            return $items;
        }

        return new \Illuminate\Support\HtmlString(
            \Illuminate\Support\Facades\View::make($type, ['items' => $items, 'options' => $options])->render()
        );
    }
}