<?php

namespace MkamelMasoud\StarterCoreKit\Middleware;

use Closure;
use Illuminate\Http\Request;
use MkamelMasoud\StarterCoreKit\Interfaces\MiddlewareInterface;
use Symfony\Component\HttpFoundation\Response;
use File;

class ClearLoggerMiddleware implements MiddlewareInterface
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
