<?php

declare(strict_types=1);

use Illuminate\Support\Carbon;

if (! function_exists('call_if_exists')) {
    /**
     * Safely call a method on an object if it exists.
     *
     * @param  object  $object  The target object.
     * @param  string  $method  The method name to call.
     * @param  mixed  ...$args  Arguments to pass to the method.
     * @return mixed|null The method result or null if not callable.
     */
    function call_if_exists(object $object, string $method, ...$args): mixed
    {
        if (method_exists($object, $method) && is_callable([$object, $method])) {
            /** @phpstan-ignore-next-line */
            return $object->{$method}(...$args);
        }

        return null;
    }
}

if (! function_exists('resolveIsCacheEnabled')) {
    function resolveIsCacheEnabled(): bool
    {
        return config('starter-core-kit.cache_results.enabled', false);
    }
}

if (! function_exists('resolveCacheTtl')) {
    function resolveCacheTtl(): Carbon
    {
        return now()->addMinutes((int) config('starter-core-kit.cache_results.ttl', 1));
    }
}
