<?php

namespace Mkamel\StarterCoreKit\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocaleFromHeader
{
    public function handle(Request $request, Closure $next)
    {
        // Get locale from header or fallback to default
        $locale = $request->header('Accept-Language', config('app.locale'));

        if (in_array($locale, ['en', 'ar'])) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
