<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';
    public const DASHBOARD = '/dashboard';
    public const SERVICES_DASHBOARD = '/services-app/dashboard';
    public const STORE_DASHBOARD = '/store-app/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('api', 'access_services_app')
                ->prefix('api')
                ->group(base_path('routes/services-app-api.php'));

            Route::middleware('api', 'access_store_app')
                ->prefix('api/store-app')
                ->group(base_path('routes/store-app-api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('web')
                ->prefix('dashboard')
                ->group(base_path('routes/dashboard.php'));

            Route::middleware('web')
                ->prefix('services-app/dashboard')
                ->group(base_path('routes/services-app.php'));

            Route::middleware('web')
                ->prefix('store-app/dashboard')
                ->group(base_path('routes/store-app.php'));
        });
    }
}
