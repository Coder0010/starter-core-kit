<?php

namespace MkamelMasoud\StarterCoreKit\Providers;

use Illuminate\Foundation\Application as ApplicationFoundation;
// use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

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
     * Register Blade directives.
     */
    protected function registerBladeDirectives(): void
    {
        if (! Blade::check('env')) {
            Blade::if('env', fn (string $env) => app()->environment($env));
        }
    }
}
