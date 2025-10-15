<?php

namespace MkamelMasoud\StarterCoreKit;

use Illuminate\Contracts\Debug\ExceptionHandler as LaravelContractExceptionHandler;
use Illuminate\Support\ServiceProvider;
use MkamelMasoud\StarterCoreKit\ExceptionHandler as CoreStarterKitExceptionHandler;
use MkamelMasoud\StarterCoreKit\Middleware\ApiCheckHeadersMiddleware;
use MkamelMasoud\StarterCoreKit\Middleware\ClearLoggerMiddleware;
use MkamelMasoud\StarterCoreKit\Middleware\SetLocaleFromHeaderMiddleware;

class PackageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LaravelContractExceptionHandler::class, CoreStarterKitExceptionHandler::class);

        // Merge package config from the correct path
        $this->mergeConfigFrom(__DIR__ . '/Config/config.php', 'starter-core-kit');
    }

    public function boot(): void
    {
        // Register middleware
        $kernel = app(\Illuminate\Contracts\Http\Kernel::class);

        $mw = config('starter-core-kit.middlewares', []);
        collect([
            SetLocaleFromHeaderMiddleware::class => $mw['set_locale'] ?? true,
            ClearLoggerMiddleware::class         => $mw['clear_logger'] ?? false,
            ApiCheckHeadersMiddleware::class     => $mw['api_check_headers'] ?? true,
        ])
            ->filter() // keeps only truthy values
            ->each(fn($enabled, $middlewareClass) => $kernel->pushMiddleware($middlewareClass));

        // Publish migrations and seeders together with one tag
        $this->publishes([
            __DIR__ . '/Config/config.php' => config_path('starter-core-kit.php'),
            __DIR__ . '/Config/repositories.php' => config_path('repositories.php'),
            __DIR__ . '/Lang' => resource_path('lang/starter-core-kit'),
        ], 'starter-core-kit');

        // Load translations
        $this->loadTranslationsFrom(__DIR__ . '/Lang', 'starter-core-kit');

        // // Load routes
        // $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        // // Load views with namespace
        // $this->loadViewsFrom(__DIR__.'/resources/views', 'starter-core-kit');

        // // Load package migrations directly (so they run without publishing)
        // $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // Bind repositories
        $this->bindRepositories();
    }

    public function bindRepositories(): void
    {
         foreach (config("repositories", []) as $contact => $eloquent) {
             $this->app->singleton($contact, $eloquent);
         }
    }
}
