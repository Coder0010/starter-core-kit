<?php

namespace MkamelMasoud\StarterCoreKit\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as LaravelHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends LaravelHandler
{
    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            $exceptionResponses = (array) config('starter-core-kit.exceptions');
            $appDebug = (bool) config('app.debug');
            foreach ($exceptionResponses as $exceptionClass => $response) {
                if (is_string($exceptionClass) && class_exists($exceptionClass) && $e instanceof $exceptionClass) {
                    $customPayload = [
                        'status_code' => (int) $response['status_code'],
                        'message' => __($response['message']),
                    ];

                    if ($appDebug) {
                        $customPayload['exception_type'] = 'Custom Exception';
                        $customPayload['file'] = $e->getFile();
                    }

                    if ($e instanceof ValidationException) {
                        $customPayload['errors'] = $e->errors();
                    }

                    return response()->json($customPayload, $response['status_code']);
                }
            }

            logger()->warning($e);

            $defaultPayload = [
                'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $appDebug ? $e->getMessage() : __(
                    'starter-core-kit::exceptions.internal_server_error'
                ),
            ];
            if ($appDebug) {
                $defaultPayload['exception_type'] = 'Default Exception';
                $defaultPayload['file'] = $e->getFile();
            }

            return response()->json($defaultPayload, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return parent::render($request, $e);
    }
}
