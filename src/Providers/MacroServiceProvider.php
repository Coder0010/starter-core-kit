<?php

namespace MkamelMasoud\StarterCoreKit\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class MacroServiceProvider
 *
 * Registers custom Laravel macros for Route and Collection.
 * This keeps your global helpers isolated and reusable.
 *
 * @property Application $app
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
    }

    /**
     * Register Route macros.
     */
    protected function registerRouteMacros(): void
    {
        if (!Route::hasMacro('when')) {
            Route::macro('when', function ($condition, callable $callback) {
                if ($condition) {
                    $callback();
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
        if (!Collection::hasMacro('paginateOnCollection')) {
            Collection::macro('paginateOnCollection', function ($perPage, $pageName = 'page', $page = null) {
                $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
                return new LengthAwarePaginator(
                    $this->forPage($page, $perPage),
                    $this->count(),
                    $perPage,
                    $page,
                    ['path' => LengthAwarePaginator::resolveCurrentPath()]
                );
            });
        }
    }
}
