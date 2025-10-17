<?php

namespace MkamelMasoud\StarterCoreKit\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler as LaravelContractExceptionHandler;
use MkamelMasoud\StarterCoreKit\ExceptionHandler as CoreStarterKitExceptionHandler;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class ExceptionServiceProvider
 *
 * Overrides Laravel's default exception handler
 * with the custom CoreStarterKit handler.
 *
 * @property Application $app
 */
class ExceptionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LaravelContractExceptionHandler::class,
            CoreStarterKitExceptionHandler::class);
    }

    public function boot(): void
    {

    }
}
