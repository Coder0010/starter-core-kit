<?php

namespace MkamelMasoud\StarterCoreKit\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MkamelMasoud\StarterCoreKit\Contracts\MiddlewareContract;
use Symfony\Component\HttpFoundation\Response;

class ApiCheckHeadersMiddleware implements MiddlewareContract
{
    public function handle(Request $request, Closure $next): Response
    {
        // Skip validation for non-API routes
        if (!str_starts_with($request->path(), 'api/')) {
            return $next($request);
        }

        // Check for required headers
        if (!$this->hasValidHeaders($request)) {
            return $this->createErrorResponse();
        }

        return $next($request);
    }

    private function hasValidHeaders(Request $request): bool
    {
        return $request->hasHeader('Accept')
            && $request->header('Accept') === 'application/json'
            && $request->hasHeader('Content-Type')
            && $request->header('Content-Type') === 'application/json'
            && $request->expectsJson();
    }

    private function createErrorResponse(): JsonResponse
    {
        return response()->json([
            'status' => Response::HTTP_BAD_REQUEST,
            'message' => __("starter-core-kit::exceptions.missing_api_headers"),
        ], Response::HTTP_BAD_REQUEST);
    }
}
