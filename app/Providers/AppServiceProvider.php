<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'order' => \App\Models\Order::class,
            'store_order' => \App\Models\StoreOrder::class,
            'user' => User::class,
            'notification' => \App\Models\Notification::class
        ]);
    }
}
