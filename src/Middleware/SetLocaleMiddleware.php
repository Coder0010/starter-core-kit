<?php

namespace MkamelMasoud\StarterCoreKit\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use MkamelMasoud\StarterCoreKit\Contracts\MiddlewareContract;

class SetLocaleMiddleware implements MiddlewareContract
{
    public function handle(Request $request, Closure $next): Response
    {
        // Priority: query > session > header > default
        $locale = $request->query('locale')           // ?locale=ar (explicit)
            ?? ($request->hasSession() ? session('locale') : null)  // previously saved
            ?? $request->header('Accept-Language')    // browser / client header
            ?? config('app.locale');                  // fallback default

        static $supportedLocales = null;
        if ($supportedLocales === null) {
            $supportedLocales = config('starter-core-kit.supported_locales', ['en', 'ar']);
        }

        if (in_array($locale, $supportedLocales, true)) {
            app()->setLocale($locale);

            // persist only if session exists (web)
            if ($request->hasSession() && $request->query('locale')) {
                session(['locale' => $locale]);
            }
        }

        return $next($request);
    }

}
