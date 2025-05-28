<?php

namespace Mkamel\StarterCoreKit\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiCheckHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (str_starts_with($request->path(), 'api/')) {
            if (
                !$request->hasHeader('Accept') || $request->header('Accept') !== 'application/json' ||
                !$request->hasHeader('Content-Type') || $request->header('Content-Type') !== 'application/json' ||
                !$request->expectsJson() 
            ) {
                return response()->json([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => __("starter-core-kit::exceptions.missing_api_headers"),
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        return $next($request);
    }
}
