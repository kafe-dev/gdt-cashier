<?php

namespace App\Provider;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Mailjet\LaravelMailjet\Facades\Mailjet;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $loader = AliasLoader::getInstance();

        $loader->alias('Mailjet', Mailjet::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
