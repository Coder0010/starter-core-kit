<?php

namespace MkamelMasoud\StarterCoreKit;

use Illuminate\Foundation\Application as ApplicationFoundation;
use Illuminate\Support\ServiceProvider;
use MkamelMasoud\StarterCoreKit\Providers\ConfigServiceProvider;
use MkamelMasoud\StarterCoreKit\Providers\ExceptionServiceProvider;
use MkamelMasoud\StarterCoreKit\Providers\MacroServiceProvider;
use MkamelMasoud\StarterCoreKit\Providers\MiddlewareServiceProvider;
use MkamelMasoud\StarterCoreKit\Providers\RepositoryServiceProvider;
use MkamelMasoud\StarterCoreKit\Providers\ResourceServiceProvider;
use MkamelMasoud\StarterCoreKit\Providers\SupportServiceProvider;
use MkamelMasoud\StarterCoreKit\Services\Ai\AiClientService;

/**
 * Class PackageServiceProvider
 *
 * The main entry point for the StarterCoreKit package.
 * It registers all internal providers and manages publishing.
 *
 * @property ApplicationFoundation $app
 */
class PackageServiceProvider extends ServiceProvider
{
    /**
     * List of internal sub-providers to register.
     *
     * @var array<class-string<ServiceProvider>>
     */
    protected array $providers = [
        ConfigServiceProvider::class,
        ExceptionServiceProvider::class,
        MiddlewareServiceProvider::class,
        RepositoryServiceProvider::class,
        ResourceServiceProvider::class,
        MacroServiceProvider::class,
    ];

    /**
     * Register all internal service providers.
     */
    public function register(): void
    {
        // Always needed providers
        $this->app->register(ConfigServiceProvider::class);
        $this->app->register(ExceptionServiceProvider::class);
        $this->app->register(RepositoryServiceProvider::class);

        // Register conditionally
        if ($this->app->runningInConsole()) {
            $this->app->register(ResourceServiceProvider::class);
        }

        $this->app->register(MiddlewareServiceProvider::class);
        $this->app->register(MacroServiceProvider::class);
        $this->app->register(SupportServiceProvider::class);

        $this->app->singleton('ai-client', fn () => new AiClientService);
    }

    /**
     * Boot the main package and handle publishing.
     */
    public function boot(): void
    {
        $basePath = __DIR__;

        // ✅ Centralized publishing configuration
        if ($this->app->runningInConsole()) {
            $this->publishes([
                "{$basePath}/config/config.php" => config_path('starter-core-kit.php'),
                "{$basePath}/config/repositories.php" => config_path('repositories.php'),
                "{$basePath}/lang" => resource_path('lang/vendor/starter-core-kit'),
            ], 'starter-core-kit');
        }

        // Load translations (if needed globally)
        $this->loadTranslationsFrom("{$basePath}/lang", 'starter-core-kit');
    }
}
