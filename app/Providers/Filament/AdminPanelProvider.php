<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationItem;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\MenuItem;
use App\Filament\Pages\Settings;



class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Blue,
            ])->font('Nunito')

            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            // ->sidebarCollapsibleOnDesktop()
            // ->sidebarFullyCollapsibleOnDesktop()
            ->navigationItems([
                NavigationItem::make('Blog')
                ->url('/admin/dashboard', shouldOpenInNewTab: true)
                ->icon('heroicon-o-pencil-square')
                ->group('Blog')
                ->sort(1),


            ])

            ->userMenuItems(items: [
                MenuItem::make()
                ->label('Settings')
                // ->url(fn (): string => Settings::getUrl())
                ->icon('heroicon-m-cog-8-tooth'),
                'logout' => MenuItem::make()->label('Logout'),


                // MenuItem::make()->label('Logout')->url('/logout')->icon('heroicon-o-logout'),
            ])

            // ->brodcrumbs(false)
            // poppins
            ->favicon('images/favicon.png')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
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
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
