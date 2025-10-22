<?php

namespace MkamelMasoud\StarterCoreKit\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class SupportServiceProvider
 *
 * Handles helpers for the project.
 */
class SupportServiceProvider extends ServiceProvider
{
    /**
     * Register helpers and small support macros.
     */
    public function register(): void
    {
        $this->loadHelpers();
    }

    /**
     * Bootstrap any lightweight macros or Blade directives.
     */
    public function boot(): void
    {
        // Keep macros here if you want them available early (before boot)
    }

    /**
     * Dynamically load all helper PHP files.
     */
    protected function loadHelpers(): void
    {
        $helpersPath = __DIR__.'/../Support/helpers';

        if (! is_dir($helpersPath)) {
            return;
        }

        foreach (glob($helpersPath.'/*.php') as $file) {
            require_once $file;
        }
    }
}
