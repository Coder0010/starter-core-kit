<?php

namespace Mkamel\StarterCoreKit\app\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as LaravelHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use TypeError;
use ValueError;

class ExceptionHandler extends LaravelHandler
{
    protected array $exceptionResponses = [
        NotFoundHttpException::class => [
            'status'  => Response::HTTP_NOT_FOUND,
            'message' => 'exceptions.route_not_found',
        ],
        MethodNotAllowedHttpException::class => [
            'status'  => Response::HTTP_METHOD_NOT_ALLOWED,
            'message' => 'exceptions.method_not_allowed',
        ],
        AuthorizationException::class => [
            'status'  => Response::HTTP_FORBIDDEN,
            'message' => 'exceptions.unauthorized',
        ],
        AuthenticationException::class => [
            'status'  => Response::HTTP_UNAUTHORIZED,
            'message' => 'exceptions.unauthenticated',
        ],
        ThrottleRequestsException::class => [
            'status'  => Response::HTTP_TOO_MANY_REQUESTS,
            'message' => 'exceptions.too_many_requests',
        ],
        RelationNotFoundException::class => [
            'status'  => Response::HTTP_NOT_FOUND,
            'message' => 'exceptions.relation_not_found',
        ],
        TypeError::class => [
            'status'  => Response::HTTP_BAD_REQUEST,
            'message' => 'exceptions.type_error',
        ],
        ValueError::class => [
            'status'  => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message' => 'exceptions.value_error',
        ],
        ValidationException::class => [
            'status'  => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message' => 'exceptions.validation_error',
        ],
    ];

    public function register(): void
    {
        $this->renderable(function (Throwable $e, Request $request) {
            if (! $request->expectsJson()) {
                return null;
            }

            $isEnvLocal = config('app.env') === 'local';
            \Log::warning($e);

            foreach ($this->exceptionResponses as $exceptionClass => $response) {
                if ($e instanceof $exceptionClass) {
                    $payload = [
                        'status'  => $response['status'],
                        'env'     => $isEnvLocal,
                        'message' => !$isEnvLocal ? "EXCEPTION:- ( ".$e->getMessage()." )" : $response['message'],
                    ];

                    if ($e instanceof ValidationException) {
                        $payload['errors'] = $e->errors();
                    }

                    return response()->json($payload, $response['status']);
                }
            }

            return response()->json([
                'status'  => Response::HTTP_INTERNAL_SERVER_ERROR,
                'env'     => $isEnvLocal,
                'message' => $isEnvLocal ? "Default EXCEPTION:- ( ".$e->getMessage()." )" :__('exceptions.unexpected_error'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        });
    }
}
