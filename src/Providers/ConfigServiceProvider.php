<?php

namespace MkamelMasoud\StarterCoreKit\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class ConfigServiceProvider
 *
 * Handles configuration merging for the package.
 */
class ConfigServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (!$this->app->configurationIsCached()) {
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
        return __DIR__ . '/../' . ltrim($path, '/');
    }
}
