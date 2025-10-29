<?php

namespace MkamelMasoud\StarterCoreKit\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use MkamelMasoud\StarterCoreKit\Rules\Validation\NoHtmlRule;

/**
 * Class ValidaterServiceProvider
 *
 * Registers custom validation rules for the package.
 */
class ValidaterServiceProvider extends ServiceProvider
{
    /**
     * Register any services.
     */
    public function register(): void
    {
        // Nothing needed here for validation rules
    }

    /**
     * Bootstrap validation rules.
     */
    public function boot(): void
    {
        /**
         * no_html
         * Maps string rule "no_html" to NoHtmlRule
         */
        Validator::extend('no_html', function (
            string $attribute,
            mixed $value,
            array $parameters,
            $validator
        ) {
            $rule = NoHtmlRule::handle();

            $failed = false;

            $rule->validate($attribute, $value, function () use (&$failed) {
                $failed = true;
            });

            return ! $failed;
        });

        /**
         * Error message replacer
         */
        Validator::replacer('no_html', function (
            string $message,
            string $attribute
        ) {
            return __('The :attribute must not contain HTML tags.');
        });
    }
}
