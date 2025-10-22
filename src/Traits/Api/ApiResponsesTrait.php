<?php

namespace MkamelMasoud\StarterCoreKit\Traits\Api;

use Illuminate\Http\JsonResponse;

/** @phpstan-ignore trait.unused */
trait ApiResponsesTrait
{
    protected function success($message, $data = null, $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status_code' => $statusCode,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    protected function error($message, $statusCode): JsonResponse
    {
        return response()->json([
            'status_code' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }

    public function relationships(string $relationship): bool
    {
        $param = request()->get('relationships');

        if (! isset($param)) {
            return false;
        }

        $includeValues = explode(',', strtolower($param));

        return in_array(strtolower($relationship), $includeValues);
    }
}
