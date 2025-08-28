<?php

namespace MkamelMasoud\StarterCoreKit;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;
use MkamelMasoud\StarterCoreKit\Exceptions\ExceptionHandler as CoreStarterKitExceptionHandler;
use MkamelMasoud\StarterCoreKit\Middleware\ApiCheckHeadersMiddleware;
use MkamelMasoud\StarterCoreKit\Middleware\SetLocaleFromHeaderMiddleware;

class CoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ExceptionHandler::class, CoreStarterKitExceptionHandler::class);

        $this->mergeConfigFrom(__DIR__.'/config.php', 'starter-core-kit');
    }

    public function boot(): void
    {
        // Register middleware
        $kernal = app(\Illuminate\Contracts\Http\Kernel::class)
            ->pushMiddleware(SetLocaleFromHeaderMiddleware::class)
            ->pushMiddleware(ApiCheckHeadersMiddleware::class)
        ;

        // Publish migrations and seeders together with one tag
        $this->publishes([
            __DIR__.'/config.php' => config_path('starter-core-kit.php'),
            __DIR__.'/lang' => resource_path('lang/starter-core-kit'),
        ], 'starter-core-kit');

        // Load translations
        $this->loadTranslationsFrom(__DIR__.'/lang', 'starter-core-kit');

        // // Load routes
        // $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        // // Load views with namespace
        // $this->loadViewsFrom(__DIR__.'/resources/views', 'starter-core-kit');
    
        // // Load package migrations directly (so they run without publishing)
        // $this->loadMigrationsFrom(__DIR__.'/database/migrations');

    }

}
