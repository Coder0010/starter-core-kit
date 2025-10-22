<?php

namespace MkamelMasoud\StarterCoreKit\Providers;

use Illuminate\Contracts\Debug\ExceptionHandler as LaravelContractExceptionHandler;
use Illuminate\Foundation\Application as ApplicationFoundation;
// use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Support\ServiceProvider;
use MkamelMasoud\StarterCoreKit\Exceptions\Handler as CoreStarterKitExceptionHandler;

/**
 * Class ExceptionServiceProvider
 *
 * Overrides Laravel's default exception handler
 * with the custom CoreStarterKit handler.
 *
 * @property ApplicationFoundation $app
 */
class ExceptionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LaravelContractExceptionHandler::class,
            CoreStarterKitExceptionHandler::class);
    }

    public function boot(): void {}
}
