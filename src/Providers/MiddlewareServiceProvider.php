<?php

namespace MkamelMasoud\StarterCoreKit\Providers;

use Illuminate\Contracts\Http\Kernel;
// use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Foundation\Application as ApplicationFoundation;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\ServiceProvider;
use MkamelMasoud\StarterCoreKit\Middleware\ApiCheckHeadersMiddleware;
use MkamelMasoud\StarterCoreKit\Middleware\ClearLoggerMiddleware;
use MkamelMasoud\StarterCoreKit\Middleware\SetLocaleMiddleware;

/**
 * Class MiddlewareServiceProvider
 *
 * Dynamically registers core middleware based on configuration.
 *
 * @property ApplicationFoundation $app
 */
class MiddlewareServiceProvider extends ServiceProvider
{
    protected static bool $middlewareRegistered = false;

    public function register(): void {}

    public function boot(): void
    {
        if (self::$middlewareRegistered) {
            return;
        }

        $kernel = app(Kernel::class);
        $middlewares = [
            ClearLoggerMiddleware::class => config('starter-core-kit.middlewares.clear_logger', false),
            ApiCheckHeadersMiddleware::class => config('starter-core-kit.middlewares.api_check_headers', true),
        ];
        SupportCollection::make($middlewares)
            ->filter()
            ->each(fn ($enabled, $middlewareClass) => $kernel->pushMiddleware($middlewareClass));
        $kernel->appendMiddlewareToGroup('api', SetLocaleMiddleware::class);
        $kernel->appendMiddlewareToGroup('web', SetLocaleMiddleware::class);
        self::$middlewareRegistered = true;
    }
}
