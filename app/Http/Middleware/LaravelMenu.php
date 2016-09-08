<?php

namespace App\Http\Middleware;

use Closure;
use Entrust;
use Menu;

class LaravelMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //左側
        Menu::make('left', function ($menu) {
            /* @var \Lavary\Menu\Builder $menu */
            $menu->add('首頁', ['route' => 'index']);
            $menu->add('地圖', ['route' => 'map.index'])->active('map/*');
            $menu->add('攤位', ['route' => 'booth.index'])->active('booth/*');
            $menu->add('打卡集點', ['route' => 'check.index'])->active('check/*');
        });
        //右側
        Menu::make('right', function ($menu) {
            /* @var \Lavary\Menu\Builder $menu */
            //會員
            if (auth()->check()) {
                if (!auth()->user()->isConfirmed) {
                    $menu->add(
                        '<i class="ui icon alarm red"></i> 尚未完成信箱驗證',
                        [
                            'route' => 'auth.resend-confirm-mail',
                            //FIXME: menu的a.item無法透過顏色class直接設定顏色
                            'class' => 'red',
                        ]
                    );
                }
                //管理員
                if (Entrust::can('menu.view') and auth()->user()->isConfirmed) {
                    /** @var \Lavary\Menu\Builder $adminMenu */
                    $adminMenu = $menu->add('管理選單', 'javascript:void(0)');

                    if (Entrust::can(['type.manage'])) {
                        $adminMenu->add('攤位類型', ['route' => 'type.index'])->active('type/*');
                    }

                    if (Entrust::can(['point.manage'])) {
                        $adminMenu->add('打卡集點記錄', ['route' => 'point.index']);
                    }

                    if (Entrust::can(['ticket.manage'])) {
                        $adminMenu->add('抽獎券', ['route' => 'ticket.index']);
                    }

                    if (Entrust::can(['student.manage'])) {
                        $adminMenu->add('學生管理', ['route' => 'student.index']);
                    }

                    if (Entrust::can(['leaderBoard.access'])) {
                        $adminMenu->add('排行榜', ['route' => 'leaderBoard.index']);
                    }

                    if (Entrust::can(['setting.manage'])) {
                        $adminMenu->add('網站設定', ['route' => 'setting.index'])->divide();
                    }

                    if (Entrust::can(['user.manage', 'user.view'])) {
                        $adminMenu->add('會員清單', ['route' => 'user.index'])->active('user/*');
                    }

                    if (Entrust::can('role.manage')) {
                        $adminMenu->add('角色管理', ['route' => 'role.index']);
                    }

                    if (Entrust::can('log-viewer.access')) {
                        $adminMenu->add(
                            '記錄檢視器 <i class="external icon"></i>',
                            ['route' => 'log-viewer::dashboard']
                        )->link->attr('target', '_blank');
                    }
                }
                /** @var \Lavary\Menu\Builder $userMenu */
                $userMenu = $menu->add(auth()->user()->name, 'javascript:void(0)');
                $userMenu->add('個人資料', ['route' => 'profile'])->active('profile/*');
                $userMenu->add('登出', ['action' => 'Auth\AuthController@logout']);
            } else {
                //遊客
                $menu->add('登入', ['action' => 'Auth\AuthController@showLoginForm']);
            }
        });
        //Sidebar
        Menu::make('sidebar', function ($menu) {
            /* @var \Lavary\Menu\Builder $menu */
            $menu->add('首頁', ['route' => 'index'])->data('icon', 'home');
            $menu->add('地圖', ['route' => 'map.index'])->active('map/*')->data('icon', 'map outline');
            $menu->add('攤位', ['route' => 'booth.index'])->active('booth/*')->data('icon', 'marker');
            if (auth()->check()) {
                $menu->add('打卡集點', ['route' => 'check.index'])->active('check/*')->data('icon', 'checkmark box');
                if (!auth()->user()->isConfirmed) {
                    $menu->add('信箱未驗證', ['route' => 'auth.resend-confirm-mail'])->data('icon', 'red mail');
                }
                $menu->add('登出', ['action' => 'Auth\AuthController@logout'])->data('icon', 'user');
            } else {
                $menu->add('登入/註冊', ['action' => 'Auth\AuthController@showLoginForm'])->data('icon', 'user');
            }
        });

        return $next($request);
    }
}
