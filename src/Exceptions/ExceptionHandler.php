<?php

namespace Mkamel\StarterCoreKit\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as LaravelHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ExceptionHandler extends LaravelHandler
{
    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            $exceptionResponses = config('starter-core-kit.exceptions');

            foreach ($exceptionResponses as $exceptionClass => $response) {
                if ($e instanceof $exceptionClass) {
                    $payload = [
                        'type' => 'Custom Exception',
                        'status'  => $response['status'],
                        'message' => __($response['message']),
                    ];

                    if ($e instanceof ValidationException) {
                        $payload['errors'] = $e->errors();
                    }

                    return response()->json($payload, $response['status']);
                }
            }

            \Log::warning($e);

            return response()->json([
                'type' => 'Default Exception',
                'status'  => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => __('starter-core-kit::exceptions.internal_server_error'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return parent::render($request, $e);
    }
}
