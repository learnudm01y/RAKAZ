<?php

namespace App\View\Composers;

use App\Models\Menu;
use Illuminate\View\View;

class MenuComposer
{
    public function compose(View $view)
    {
        $menus = Menu::with([
                'activeColumns.items.category' => function ($query) {
                    $query->withCount(['products' => function ($q) {
                        $q->where('is_active', true);
                    }]);
                },
                'activeColumns.items.category.children' => function ($query) {
                    $query->where('is_active', true)
                        ->withCount(['products' => function ($q) {
                            $q->where('is_active', true);
                        }]);
                }
            ])
            ->active()
            ->ordered()
            ->get();

        $view->with('menus', $menus);
    }
}
