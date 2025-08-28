<?php

namespace MkamelMasoud\StarterCoreKit\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as LaravelHandler;
use Illuminate\Validation\ValidationException;
use MkamelMasoud\StarterCoreKit\Interfaces\ExceptionInterface;
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
                    $customPayload = [
                        'status_code' => $response['status_code'],
                        'message' => __($response['message']),
                    ];

                    if(config('app.debug')){
                        $customPayload['exception_type'] = 'Custom Exception';
                        $customPayload['file'] = $e->getFile();
                    }

                    if ($e instanceof ValidationException) {
                        $customPayload['errors'] = $e->errors();
                    }

                    return response()->json($customPayload, $response['status_code']);
                }
            }

            \Log::warning($e);

            $defaultPayload = [
                'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => config('app.debug') ? $e->getMessage() : __('starter-core-kit::exceptions.internal_server_error'),
            ];
            if(config('app.debug')){
                $defaultPayload['exception_type'] = 'Default Exception';
                $defaultPayload['file'] = $e->getFile();
            }
            return response()->json($defaultPayload, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return parent::render($request, $e);
    }
}
