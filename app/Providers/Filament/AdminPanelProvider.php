<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->sidebarFullyCollapsibleOnDesktop()
            ->sidebarWidth('15rem')
            ->path('admin')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->brandLogo(fn () => new \Illuminate\Support\HtmlString('
                <div class="flex items-center gap-2 font-bold text-xl tracking-tight text-gray-900 dark:text-white" style="display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 1.25rem; tracking: -0.025em;">
                    <span class="w-8 h-8 rounded-lg bg-blue-600 dark:bg-blue-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20" style="width: 32px; height: 32px; border-radius: 8px; background-color: rgb(37 99 235); display: flex; align-items: center; justify-content: center; color: white;">
                        <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.62 48.62 0 0112 20.9c4.168 0 7.828-2.122 9.96-5.328a60.428 60.428 0 00-.49-6.347m-15.7 3.393l.334 2.228m-1.5-3.375a9.045 9.045 0 011.528-3.087m0 0L12 3.126l7.733 4.305m-7.733-4.305l-7.732 4.305m0 0a8.91 8.91 0 00-.12 1.229v.203m15.464-3.417A9.045 9.045 0 0120.4 12m0 0l.334 2.228m-1.5-3.375a9.045 9.045 0 011.528-3.087m-1.531 6.462a48.62 48.62 0 00-6.733-3.087m0 0L12 3.126m0 0v17.774" />
                        </svg>
                    </span>
                    <span style="font-size: 20px; font-weight: bold;">Acadomart</span>
                </div>
            '))
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\Filament\Admin\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\Filament\Admin\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\Filament\Admin\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
