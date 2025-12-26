<?php

namespace App\View\Composers;

use App\Models\Menu;
use Illuminate\View\View;

class MenuComposer
{
    public function compose(View $view)
    {
        $menus = Menu::with(['activeColumns.items.category.children'])
            ->active()
            ->ordered()
            ->get();

        $view->with('menus', $menus);
    }
}
