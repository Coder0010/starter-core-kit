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
    public function register(): void
    {
        $this->renderable(function (Throwable $e, Request $request) {
            if (! $request->expectsJson()) {
                return null;
            }

            return $this->buildJsonResponse($e);
        });
    }

    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            return $this->buildJsonResponse($e);
        }

        return parent::render($request, $e);
    }

    protected function buildJsonResponse(Throwable $e): JsonResponse
    {
        $exceptionResponses = config('starter-core-kit.exceptions');
        $isEnvLocal = config('app.env') === 'local';

        foreach ($exceptionResponses as $exceptionClass => $response) {
            if ($e instanceof $exceptionClass) {
                $payload = [
                    'status'  => $response['status'],
                    'message' => $isEnvLocal ? "Laravel Exception is:- ( ".$e->getMessage()." )" : __($response['message']),
                ];

                if ($e instanceof ValidationException) {
                    $payload['errors'] = $e->errors();
                }

                return response()->json($payload, $response['status']);
            }
        }

        \Log::warning($e);

        return response()->json([
            'status'  => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => $isEnvLocal ? "Laravel Default Exception is:- ( ".$e->getMessage()." )" : __($response['message']),
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
