<?php

namespace MkamelMasoud\StarterCoreKit\Middleware;

use Closure;
use Illuminate\Http\Request;
use MkamelMasoud\StarterCoreKit\Contracts\MiddlewareContract;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromHeaderMiddleware implements MiddlewareContract
{
    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from header or fallback to default
        $locale = $request->header('Accept-Language', (string) config('app.locale'));

        // Cache supported locales for better performance
        static $supportedLocales = null;

        if ($supportedLocales === null) {
            $supportedLocales = (array) config('starter-core-kit.supported_locales');
        }

        // Only set locale if it's supported
        if (in_array($locale, $supportedLocales, true)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
