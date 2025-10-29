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

    'cache_results' => [
        'enabled' => env('APP_CACHE_RESULTS_ENABLED', false),
        'ttl' => env('APP_CACHE_RESULTS_TTL', 1),
    ],

    'ai' => [
        'enabled' => env('AI_ENABLED', true),
        'default' => env('AI_PROVIDER', 'openai'),

        'cache' => [
            'enabled' => env('AI_CACHE_ENABLED', true),
            'ttl' => env('AI_CACHE_TTL', 600),
        ],

        'timeout' => env('AI_PROVIDER_TIMEOUT', 20),
        'retry' => [
            'attempts' => env('AI_PROVIDER_RETRY_ATTEMPTS', 2),
            'delay' => env('AI_PROVIDER_RETRY_DELAY_MS', 500),
        ],

        'logging' => [
            'enabled' => env('AI_PROVIDER_LOGGING_ENABLED', true),
        ],

        'models' =>[
            'default' => [
                'gpt-4o-mini',
                'openai/gpt-4o',
                'anthropic/claude-3.5-sonnet',
                'google/gemini-1.5-pro',
                'meta-llama/llama-3.1-70b',
                'mistralai/mixtral-8x22b',
                'deepseek/deepseek-chat',
            ],
        ],

        'providers' => [
            'openai' => [
                'api_key' => env('OPENAI_API_KEY'),
                'base_url' => env('OPENAI_BASEURL', 'https://api.openai.com'),
                'version' => env('OPENAI_VERSION', 'v1'),
                'end_point' => env('OPENAI_ENDPOINT', 'chat/completions'),
                'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
            ],
            'router' => [
                'api_key' => env('ROUTER_API_KEY'),
                'base_url' => env('ROUTER_BASEURL', 'https://openrouter.ai/api'),
                'version' => env('ROUTER_VERSION', 'v1'),
                'end_point' => env('ROUTER_ENDPOINT', 'chat/completions'),
                'model' => env('ROUTER_MODEL', 'gpt-4o-mini'),
            ],
        ],
    ],

    'exceptions' => [
        \Illuminate\Database\Eloquent\ModelNotFoundException::class => [
            'status_code' => Response::HTTP_NOT_FOUND,
            'message' => 'starter-core-kit::exceptions.model_not_found',
        ],
        \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class => [
            'status_code' => Response::HTTP_NOT_FOUND,
            'message' => 'starter-core-kit::exceptions.route_not_found',
        ],
        \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException::class => [
            'status_code' => Response::HTTP_METHOD_NOT_ALLOWED,
            'message' => 'starter-core-kit::exceptions.method_not_allowed',
        ],
        \Illuminate\Validation\ValidationException::class => [
            'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message' => 'starter-core-kit::exceptions.validation_error',
        ],
        \Illuminate\Auth\Access\AuthorizationException::class => [
            'status_code' => Response::HTTP_UNAUTHORIZED,
            'message' => 'starter-core-kit::exceptions.unauthorized',
        ],
        \Illuminate\Auth\AuthenticationException::class => [
            'status_code' => Response::HTTP_FORBIDDEN,
            'message' => 'starter-core-kit::exceptions.unauthenticated',
        ],
        \Illuminate\Http\Exceptions\ThrottleRequestsException::class => [
            'status_code' => Response::HTTP_TOO_MANY_REQUESTS,
            'message' => 'starter-core-kit::exceptions.too_many_requests',
        ],
        \Illuminate\Database\Eloquent\RelationNotFoundException::class => [
            'status_code' => Response::HTTP_NOT_FOUND,
            'message' => 'starter-core-kit::exceptions.relation_not_found',
        ],
        \TypeError::class => [
            'status_code' => Response::HTTP_BAD_REQUEST,
            'message' => 'starter-core-kit::exceptions.type_error',
        ],
        \ValueError::class => [
            'status_code' => Response::HTTP_BAD_REQUEST,
            'message' => 'starter-core-kit::exceptions.value_error',
        ],
        \Symfony\Component\ErrorHandler\Error\FatalError::class => [
            'status_code' => Response::HTTP_BAD_REQUEST,
            'message' => 'starter-core-kit::exceptions.fatal_error',
        ],
        \Illuminate\Contracts\Filesystem\FileNotFoundException::class => [
            'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message' => 'starter-core-kit::exceptions.file_not_found',
        ],
        \PDOException::class => [
            'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => 'starter-core-kit::exceptions.pdo_exception',
        ],
    ],
];
