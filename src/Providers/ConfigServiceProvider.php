<?php

namespace MkamelMasoud\StarterCoreKit\Providers;

use Illuminate\Foundation\Application as ApplicationFoundation;
use Illuminate\Support\ServiceProvider;

/**
 * Class ConfigServiceProvider
 *
 * Handles configuration merging for the package.
 *
 * @property ApplicationFoundation $app
 */
class ConfigServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (! $this->app->configurationIsCached()) {
            $this->mergeConfigFrom($this->packagePath('config/config.php'), 'starter-core-kit');
            $this->mergeConfigFrom($this->packagePath('config/repositories.php'), 'repositories');
        }
    }

    public function boot(): void
    {
        // No publishing here — handled by main provider
    }

    protected function packagePath(string $path): string
    {
        return __DIR__.'/../'.ltrim($path, '/');
    }
}
