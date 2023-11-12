<?php

namespace Modules\Feedback\Http\Middleware;

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
            //feedback menu

            $menu->add('<i class="fas fa-comment c-sidebar-nav-icon"></i> '.trans('menu.feedback.remarks'), [
                'route' => 'backend.remarks.index',
                'class' => 'c-sidebar-nav-item',
            ])
            ->data([
                'order' => 5,
                'activematches' => ['admin/remarks*'],
                'permission' => ['view_remarks'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

        })->sortBy('order');

        return $next($request);
    }
}
