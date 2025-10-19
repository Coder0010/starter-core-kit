<?php

use Illuminate\Http\Response;

return [
    'version' => '1.0.0',

    'supported_locales' => ['en', 'ar'],

    'full_auth_modules' => env('APP_FULL_AUTH_MODULES', false),

    'middlewares' => [
        'set_locale' => env('SET_LOCALE', true),
        'clear_logger' => env('CLEAR_LOGGER', false),
        'api_check_headers' => env('API_CHECK_HEADERS', true),
    ],

    'exceptions' => [
        \Illuminate\Database\Eloquent\ModelNotFoundException::class => [
            'status_code' => Response::HTTP_NOT_FOUND,
            'message' => 'starter-core-kit::exceptions.model_not_found',
        ],
        \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class => [
            'status_code'  => Response::HTTP_NOT_FOUND,
            'message' => 'starter-core-kit::exceptions.route_not_found',
        ],
        \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException::class => [
            'status_code'  => Response::HTTP_METHOD_NOT_ALLOWED,
            'message' => 'starter-core-kit::exceptions.method_not_allowed',
        ],
        \Illuminate\Validation\ValidationException::class => [
            'status_code'  => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message' => 'starter-core-kit::exceptions.validation_error',
        ],
        \Illuminate\Auth\Access\AuthorizationException::class => [
            'status_code'  => Response::HTTP_UNAUTHORIZED,
            'message' => 'starter-core-kit::exceptions.unauthorized',
        ],
        \Illuminate\Auth\AuthenticationException::class => [
            'status_code'  => Response::HTTP_FORBIDDEN,
            'message' => 'starter-core-kit::exceptions.unauthenticated',
        ],
        \Illuminate\Http\Exceptions\ThrottleRequestsException::class => [
            'status_code'  => Response::HTTP_TOO_MANY_REQUESTS,
            'message' => 'starter-core-kit::exceptions.too_many_requests',
        ],
        \Illuminate\Database\Eloquent\RelationNotFoundException::class => [
            'status_code'  => Response::HTTP_NOT_FOUND,
            'message' => 'starter-core-kit::exceptions.relation_not_found',
        ],
        \TypeError::class => [
            'status_code'  => Response::HTTP_BAD_REQUEST,
            'message' => 'starter-core-kit::exceptions.type_error',
        ],
        \ValueError::class => [
            'status_code'  => Response::HTTP_BAD_REQUEST,
            'message' => 'starter-core-kit::exceptions.value_error',
        ],
        \Symfony\Component\ErrorHandler\Error\FatalError::class => [
            'status_code'  => Response::HTTP_BAD_REQUEST,
            'message' => 'starter-core-kit::exceptions.fatal_error',
        ],
        \Illuminate\Contracts\Filesystem\FileNotFoundException::class => [
            'status_code'  => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message' => 'starter-core-kit::exceptions.file_not_found',
        ],
        \PDOException::class => [
            'status_code'  => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => 'starter-core-kit::exceptions.pdo_exception',
        ],
    ],
];
