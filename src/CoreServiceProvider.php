<?php

namespace Mkamel\StarterCoreKit;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;
use Mkamel\StarterCoreKit\Exceptions\ExceptionHandler as CoreKitHandler;
use Mkamel\StarterCoreKit\Middleware\ApiCheckHeaders;
use Mkamel\StarterCoreKit\Middleware\SetLocaleFromHeader;

class CoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ExceptionHandler::class, CoreKitHandler::class);

        $this->mergeConfigFrom(__DIR__.'/config.php', 'starter-core-kit');
        
    }

    public function boot(): void
    {
        // Register middleware
        $kernel = app(\Illuminate\Contracts\Http\Kernel::class)
            ->pushMiddleware(ApiCheckHeaders::class)
            ->pushMiddleware(SetLocaleFromHeader::class);

        // Publish migrations and seeders together with one tag
        $this->publishes([
            __DIR__.'/config.php' => config_path('starter-core-kit.php'),
            __DIR__.'/lang' => resource_path('lang/starter-core-kit'),
            // __DIR__.'/database/migrations/2025_05_26_0001_create_exceptions_table.php' => database_path('migrations/2025_05_26_0001_create_exceptions_table.php'),
            // __DIR__.'/database/seeders/ExceptionRecordSeeder.php' => database_path('seeders/ExceptionRecordSeeder.php'),
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
