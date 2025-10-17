<?php

namespace MkamelMasoud\StarterCoreKit\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class ResourceServiceProvider
 *
 * Handles loading of translations, views, and other resources.
 */
class ResourceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Nothing here yet — reserved for later if needed
    }

    public function boot(): void
    {
        // Just load resources here — no publishes
        $this->loadTranslationsFrom($this->packagePath('lang'), 'starter-core-kit');
    }

    protected function packagePath(string $path): string
    {
        return __DIR__ . '/../' . ltrim($path, '/');
    }
}
