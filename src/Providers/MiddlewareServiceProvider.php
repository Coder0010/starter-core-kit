<?php

namespace MkamelMasoud\StarterCoreKit\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Contracts\Foundation\Application;
use MkamelMasoud\StarterCoreKit\Middleware\{
    ApiCheckHeadersMiddleware,
    ClearLoggerMiddleware,
    SetLocaleFromHeaderMiddleware
};

/**
 * Class MiddlewareServiceProvider
 *
 * Dynamically registers core middleware based on configuration.
 *
 * @property Application $app
 */
class MiddlewareServiceProvider extends ServiceProvider
{
    protected static bool $middlewareRegistered = false;

    public function register(): void
    {

    }

    public function boot(): void
    {
        if (self::$middlewareRegistered) {
            return;
        }

        $kernel = app(Kernel::class);
        $middlewares = [
            SetLocaleFromHeaderMiddleware::class => config('starter-core-kit.middlewares.set_locale', true),
            ClearLoggerMiddleware::class         => config('starter-core-kit.middlewares.clear_logger', false),
            ApiCheckHeadersMiddleware::class     => config('starter-core-kit.middlewares.api_check_headers', true),
        ];
        Collection::make($middlewares)
            ->filter()
            ->each(fn($enabled, $middlewareClass) => $kernel->pushMiddleware($middlewareClass));
        self::$middlewareRegistered = true;


    }

}
