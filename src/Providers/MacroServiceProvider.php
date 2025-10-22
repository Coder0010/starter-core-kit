<?php

namespace MkamelMasoud\StarterCoreKit\Providers;

use Closure;
use Illuminate\Foundation\Application as ApplicationFoundation;
// use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Throwable;

/**
 * Class MacroServiceProvider
 *
 * Registers custom Laravel macros for Route and Collection.
 * This keeps your global helpers isolated and reusable.
 *
 * @property ApplicationFoundation $app
 */
class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register any macros.
     */
    public function register(): void
    {
        // Keep macros here if you want them available early (before boot)
    }

    /**
     * Bootstrap macros after all other services are registered.
     */
    public function boot(): void
    {
        $this->registerRouteMacros();
        $this->registerCollectionMacros();
        $this->registerSafeTransaction();
    }

    /**
     * Register Route macros.
     */
    protected function registerRouteMacros(): void
    {
        if (! Route::hasMacro('when')) {
            Route::macro('when', function ($condition, callable $callBack) {
                if ($condition) {
                    $callBack();
                }

                return Route::getRoutes();
            });
        }
    }

    /**
     * Register Collection macros.
     */
    protected function registerCollectionMacros(): void
    {
        if (! SupportCollection::hasMacro('paginateOnCollection')) {
            SupportCollection::macro(
                'paginateOnCollection',
                function (int $perPage = 10, string $pageName = 'page', ?int $page = null) {
                    $page = $page ?? LengthAwarePaginator::resolveCurrentPage($pageName);

                    return new LengthAwarePaginator(
                        $this->forPage($page, $perPage),
                        $this->count(),
                        $perPage,
                        $page,
                        ['path' => LengthAwarePaginator::resolveCurrentPath()]
                    );
                }
            );
        }
    }

    /**
     * Register Safe Transaction DB macro.
     */
    protected function registerSafeTransaction(): void
    {
        if (! DB::hasMacro('safeTransaction')) {
            DB::macro('safeTransaction', function (Closure $successCallback, ?Closure $failCallback = null, int $attempts = 1): mixed {
                try {
                    return DB::transaction($successCallback, $attempts);
                } catch (Throwable $e) {
                    logger()->error($e->getMessage(), ['exception' => $e]);

                    if ($failCallback) {
                        $failCallback($e);
                    }

                    throw $e;
                }
            });
        }
    }
}
