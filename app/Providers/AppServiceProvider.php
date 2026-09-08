<?php

namespace App\Providers;

use App\Models\SubscriptionPlan;
use App\Models\Tenant;
use App\Observers\SubscriptionPlanObserver;
use App\Observers\TenantObserver;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\ServiceProvider;
use Mcamara\LaravelLocalization\Traits\LoadsTranslatedCachedRoutes;

class AppServiceProvider extends ServiceProvider
{
    use LoadsTranslatedCachedRoutes;

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
        RouteServiceProvider::loadCachedRoutesUsing(fn () => $this->loadCachedRoutes());

        SubscriptionPlan::observe(SubscriptionPlanObserver::class);
        Tenant::observe(TenantObserver::class);
    }
}
