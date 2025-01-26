<?php

namespace App\Providers;

use App\Services\TrackingMore\Requests\Courier;
use App\Services\TrackingMore\Requests\Tracking;
use App\Services\TrackingMore\TrackingMore;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Maatwebsite\Excel\Facades\Excel;
use Mailjet\LaravelMailjet\Facades\Mailjet;
use TrackingMore\Couriers;
use TrackingMore\Trackings;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $loader = AliasLoader::getInstance();

        $loader->alias('Mailjet', Mailjet::class);
        $loader->alias('Excel', Excel::class);

        $apiKey = config('tracking-more.api_key');

        $this->app->bind(Tracking::class, function () use ($apiKey) {
            return new Tracking(new Trackings($apiKey));
        });

        $this->app->bind(Courier::class, function () use ($apiKey) {
            return new Courier(new Couriers($apiKey));
        });

        $this->app->bind(TrackingMore::class, function (Application $app) {
            return new TrackingMore(tracking: $app->make(Tracking::class), courier: $app->make(Courier::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}
