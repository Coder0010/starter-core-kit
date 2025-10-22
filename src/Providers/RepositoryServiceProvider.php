<?php

namespace MkamelMasoud\StarterCoreKit\Providers;

use Illuminate\Foundation\Application as ApplicationFoundation;
// use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 *
 * Binds repository interfaces to their concrete implementations
 * based on the repositories.php configuration.
 *
 * @property ApplicationFoundation $app
 */
class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind repositories
        foreach (config('repositories', []) as $contact => $eloquent) {
            $this->app->singleton($contact, $eloquent);
        }
    }

    public function boot(): void {}
}
