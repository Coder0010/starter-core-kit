<?php

namespace MkamelMasoud\StarterCoreKit\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class RepositoryServiceProvider
 *
 * Binds repository interfaces to their concrete implementations
 * based on the repositories.php configuration.
 *
 * @property Application $app
 */
class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind repositories
        foreach (config("repositories", []) as $contact => $eloquent) {
            $this->app->singleton($contact, $eloquent);
        }
    }

    public function boot(): void
    {

    }
}
