<?php

use Illuminate\Http\Response;

return [
    'version' => '1.0.0',
    
    'exceptions' => [
        \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class => [
            'status'  => Response::HTTP_NOT_FOUND,
            'message' => 'starter-core-kit::exceptions.route_not_found',
        ],
        \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException::class => [
            'status'  => Response::HTTP_METHOD_NOT_ALLOWED,
            'message' => 'starter-core-kit::exceptions.method_not_allowed',
        ],
        \Illuminate\Validation\ValidationException::class => [
            'status'  => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message' => 'starter-core-kit::exceptions.validation_error',
        ],
        \Illuminate\Auth\Access\AuthorizationException::class => [
            'status'  => Response::HTTP_UNAUTHORIZED,
            'message' => 'starter-core-kit::exceptions.unauthorized',
        ],
        \Illuminate\Auth\AuthenticationException::class => [
            'status'  => Response::HTTP_FORBIDDEN,
            'message' => 'starter-core-kit::exceptions.unauthenticated',
        ],
        \Illuminate\Http\Exceptions\ThrottleRequestsException::class => [
            'status'  => Response::HTTP_TOO_MANY_REQUESTS,
            'message' => 'starter-core-kit::exceptions.too_many_requests',
        ],
        \Illuminate\Database\Eloquent\RelationNotFoundException::class => [
            'status'  => Response::HTTP_NOT_FOUND,
            'message' => 'starter-core-kit::exceptions.relation_not_found',
        ],
        \TypeError::class => [
            'status'  => Response::HTTP_BAD_REQUEST,
            'message' => 'starter-core-kit::exceptions.type_error',
        ],
        \ValueError::class => [
            'status'  => Response::HTTP_BAD_REQUEST,
            'message' => 'starter-core-kit::exceptions.value_error',
        ],
        \Symfony\Component\ErrorHandler\Error\FatalError::class => [
            'status'  => Response::HTTP_BAD_REQUEST,
            'message' => 'starter-core-kit::exceptions.fatal_error',
        ],
        \Illuminate\Contracts\Filesystem\FileNotFoundException::class => [
            'status'  => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message' => 'starter-core-kit::exceptions.file_not_found',
        ],
    ]
];