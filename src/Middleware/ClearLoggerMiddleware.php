<?php

namespace MkamelMasoud\StarterCoreKit\Middleware;

use Closure;
use File;
use Illuminate\Http\Request;
use MkamelMasoud\StarterCoreKit\Contracts\MiddlewareContract;
use Symfony\Component\HttpFoundation\Response;

class ClearLoggerMiddleware implements MiddlewareContract
{
    public function handle(Request $request, Closure $next): Response
    {
        $logPath = storage_path('logs/laravel.log');

        if (File::exists($logPath)) {
            File::put($logPath, '');
        }

        return $next($request);
    }
}
