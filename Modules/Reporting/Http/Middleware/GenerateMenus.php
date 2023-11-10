<?php

namespace Modules\Reporting\Http\Middleware;

use Closure;

class GenerateMenus
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        \Menu::make('admin_sidebar', function ($menu) {
            //reporting menu

            $menu->add('<i class="fas fa-scroll c-sidebar-nav-icon"></i> '.trans('menu.reporting.reports'), [
                'route' => 'backend.reports.index',
                'class' => 'c-sidebar-nav-item',
            ])
            ->data([
                'order' => 3,
                'activematches' => ['admin/reports*'],
                'permission' => ['view_reports'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            $menu->add('<i class="fas fa-th c-sidebar-nav-icon"></i> '.trans('menu.reporting.types'), [
                'route' => 'backend.types.index',
                'class' => 'c-sidebar-nav-item',
            ])
            ->data([
                'order' => 4,
                'activematches' => ['admin/types*'],
                'permission' => ['view_types'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

        })->sortBy('order');

        return $next($request);
    }
}
