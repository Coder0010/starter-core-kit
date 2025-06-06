<?php

namespace Mkamel\StarterCoreKit\Middleware;

use Closure;
use Illuminate\Http\Request;
use Mkamel\StarterCoreKit\Interfaces\MiddlewareInterface;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromHeaderMiddleware implements MiddlewareInterface
{

    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from header or fallback to default
        $locale = $request->header('Accept-Language', config('app.locale'));

        // Cache supported locales for better performance
        static $supportedLocales = null;

        if ($supportedLocales === null) {
            $supportedLocales = config('starter-core-kit.supported_locales', ['en', 'ar']);
        }

        // Only set locale if it's supported
        if (in_array($locale, $supportedLocales)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
