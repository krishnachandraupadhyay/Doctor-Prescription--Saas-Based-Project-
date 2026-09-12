<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
        // Make Laravel's default pagination views render using
        // Bootstrap 5 markup instead of the default Tailwind markup,
        // since this project's UI is built with Bootstrap.
        Paginator::useBootstrapFive();

        \Illuminate\Support\Facades\Blade::if('hasFeature', function ($featureCode) {
            return \App\Services\SubscriptionService::hasFeature($featureCode);
        });

        \Illuminate\Support\Facades\Blade::if('hasActiveSubscription', function () {
            return \App\Services\SubscriptionService::hasActiveSubscription();
        });

        // Apply System Timezone dynamically
        try {
            $tz = \App\Models\SystemSetting::get('timezone', 'Asia/Kolkata');
            if (!empty($tz)) {
                date_default_timezone_set($tz);
                config(['app.timezone' => $tz]);
            }
        } catch (\Throwable $e) {
            // Fallback gracefully during migrations or setup
        }
    }
}
