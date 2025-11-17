<?php

namespace App\Providers\Filament;

use App\Models\Core\BotMessageButton;
use App\Models\Core\MiniApp;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $A = [];
        $menus = MiniApp::orderBy('id')->get();


        $A[] = NavigationItem::make('apps')
            ->label('Мини-приложения')
            ->icon('heroicon-o-square-3-stack-3d')
            ->url("/admin/mini-apps")
            ->isActiveWhen(fn () => url()->current()==env("APP_URL")."/admin/mini-apps");

        $A[] = NavigationItem::make('bots')
            ->label('Боты')
            ->icon('heroicon-o-face-smile')
            ->url("/admin/bots")
            ->isActiveWhen(fn () => url()->current()==env("APP_URL")."/admin/bots");

        $A[] = NavigationItem::make('pages')
            ->label('Страницы')
            ->icon('heroicon-o-newspaper')
            ->url("/admin/mini-app-pages")
            ->isActiveWhen(fn () => url()->current()==env("APP_URL")."/admin/mini-app-pages");

        foreach ($menus as $menu) {
            $A[] = NavigationItem::make('banners_apps')
                ->label($menu->name)
                ->group('Баннеры')
                ->icon('heroicon-o-photo')
                ->url("/admin/mini-app-banners/".$menu->id."/".rawurlencode($menu->name)."/admin_banner_by_app")
                ->isActiveWhen(fn () => url()->current()==env("APP_URL")."/admin/mini-app-banners/".$menu->id."/".rawurlencode($menu->name)."/admin_banner_by_app");
        }

//        Route::get('/banners_apps', function () {
//            return view('banners_apps');
//        })->middleware('/access');

        $A[] = NavigationItem::make('flisteners')
            ->label('Триггеры воронок')
            ->group('Справочники')
            ->icon('heroicon-o-rectangle-group')
            ->url("/admin/funnel-conditions")
            ->isActiveWhen(fn () => url()->current()==env("APP_URL")."/admin/funnel-conditions");

        $A[] = NavigationItem::make('directories')
            ->label('Назначение сообщений')
            ->group('Справочники')
            ->icon('heroicon-o-cog-8-tooth')
            ->url("/admin/bot-message-appointments")
            ->isActiveWhen(fn () => url()->current()==env("APP_URL")."/admin/bot-message-appointments");

        $A[] = NavigationItem::make('listeners')
            ->label('Ожидания')
            ->group('Справочники')
            ->icon('heroicon-o-rectangle-group')
            ->url("/admin/listeners")
            ->isActiveWhen(fn () => url()->current()==env("APP_URL")."/admin/listeners");

        $A[] = NavigationItem::make('bot-message-button-callbacks')
            ->label('Обработчики кнопок')
            ->group('Справочники')
            ->icon('heroicon-o-code-bracket-square')
            ->url("/admin/bot-message-button-callbacks")
            ->isActiveWhen(fn () => url()->current()==env("APP_URL")."/admin/bot-message-button-callbacks");

        $A[] = NavigationItem::make('variable_groups')
            ->label('Переменные')
            ->group('Настройки')
            ->icon('heroicon-o-cog-8-tooth')
            ->url("/admin/variable-groups")
            ->isActiveWhen(fn () => url()->current()==env("APP_URL")."/admin/variable-groups");

        return $panel
            ->middleware([])

            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Баннеры')
                    ->isCollapsed(true)
            ])
            ->default()
            ->navigationItems($A)
            ->sidebarCollapsibleOnDesktop()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors(['primary' => Color::Amber])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                //Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}