# 🚀 Starter Core Kit for Laravel

A comprehensive, production-ready Laravel starter package that provides essential utilities, middleware, exception handling, AI integration, and repository patterns for modern Laravel applications.

[![Latest Version](https://img.shields.io/packagist/v/mkamelmasoud/starter-core-kit.svg?style=flat-square)](https://packagist.org/packages/mkamelmasoud/starter-core-kit)
[![Total Downloads](https://img.shields.io/packagist/dt/mkamelmasoud/starter-core-kit.svg?style=flat-square)](https://packagist.org/packages/mkamelmasoud/starter-core-kit)
[![License](https://img.shields.io/packagist/l/mkamelmasoud/starter-core-kit.svg?style=flat-square)](https://packagist.org/packages/mkamelmasoud/starter-core-kit)

---

## ✨ Features

### 🛡️ Exception Handling & Middleware
- **Custom Global Exception Handler** - Centralized exception handling with JSON responses
- **API Header Validation Middleware** - Ensures proper API request headers
- **Dynamic Locale Middleware** - Automatic locale detection from headers
- **Multi-language Exception Messages** - Localized error messages (English/Arabic)
- **Comprehensive Exception Mapping** - Pre-configured responses for common Laravel exceptions

### 🤖 AI Integration
- **Multi-Provider AI Support** - OpenAI and Router.ai integration
- **Simple Factory Pattern** - Clean provider instantiation
- **AI Client Service** - Easy-to-use interface for AI interactions
- **Built-in Caching** - Automatic response caching with TTL
- **Comprehensive Logging** - Detailed request/response logging

### 🏗️ Repository & Service Patterns
- **Base Repository Pattern** - Eloquent-based repository implementation
- **Base Service Layer** - Service layer with caching and transaction support
- **CRUD Operations** - Complete Create, Read, Update, Delete functionality
- **Advanced Query Building** - Flexible filtering and data fetching
- **Caching Support** - Built-in caching with TTL configuration

### 🔧 Utilities & Helpers
- **API Response Traits** - Standardized JSON response formatting
- **File Upload Handling** - Secure file upload utilities
- **Cache Management** - Advanced caching with automatic invalidation
- **Helper Functions** - Utility functions for common operations
- **Validation Rules** - Custom validation rules (e.g., NoHtmlRule)

### 📊 Data Management
- **Pagination Support** - Built-in pagination for large datasets
- **Search Functionality** - Advanced search capabilities
- **Random Data Fetching** - Random record selection
- **Relationship Loading** - Eager loading with relationship support
- **Soft Delete Support** - Soft delete functionality

---

## 📁 Project Structure

```
starter-core-kit/
+---src
|   PackageServiceProvider.php
|
+---Contracts
|   |   AiProviderContract.php
|   |   MiddlewareContract.php
|   |   ServiceContract.php
|   |   SortableContract.php
|   |
|   \---Repositories
|           BaseRepositoryContract.php
|           DeletableRepositoryContract.php
|           ReadableRepositoryContract.php
|           WritableRepositoryContract.php
|
+---Core
|   |   BaseDto.php
|   |   BaseEntity.php
|   |   BaseService.php
|   |
|   +---Ai
|   |   |   BaseAIProvider.php
|   |   |
|   |   \---Traits
|   |           BuildRequestTrait.php
|   |           HandlesValidationTrait.php
|   |           ManageStateTrait.php
|   |
|   +---Repositories
|   |   |   BaseEloquentRepository.php
|   |   |
|   |   \---Traits
|   |           BuildQueryTrait.php
|   |
|   \---Services
|       \---Traits
|               DeletableServiceTrait.php
|               ReadableServiceTrait.php
|               WritableServiceTrait.php
|
+---Exceptions
|       AiConfigNotFoundException.php
|       AiProviderNotFoundException.php
|       Handler.php
|
+---lang
|   +---ar
|   |       exceptions.php
|   |
|   \---en
|           exceptions.php
|
+---Middleware
|       ApiCheckHeadersMiddleware.php
|       ClearLoggerMiddleware.php
|       SetLocaleFromHeaderMiddleware.php
|
+---Providers
|       ConfigServiceProvider.php
|       ExceptionServiceProvider.php
|       MacroServiceProvider.php
|       MiddlewareServiceProvider.php
|       RepositoryServiceProvider.php
|       ResourceServiceProvider.php
|       SupportServiceProvider.php
|
+---Rules
|   \---Validation
|           NoHtmlRule.php
|
+---Services
|   \---Ai
|       |   AiClientService.php
|       |
|       +---AiProviders
|       |       OpenAiProviderService.php
|       |       RouterAiProviderService.php
|       |
|       \---Providers
+---Support
|   +---Factories
|   |       AiClientFactory.php
|   |
|   \---helpers
|           functions.php
|
\---Traits
    +---Api
    |       ApiResponsesTrait.php
    |
    +---File
    |       HandleFileUploadTrait.php
    |
    +---Service
    |       ServiceSupportTrait.php
    |
    \---Support
            SupportCacheTrait.php
            SupportSortableTrait.php
```

---

## 📦 Installation

Install the package via Composer:

```bash
composer require mkamelmasoud/starter-core-kit
```

The package will automatically register its service provider.

---

## ⚙️ Configuration

Publish the configuration files:

```bash
php artisan vendor:publish --tag=starter-core-kit
```

This will publish:
- `config/starter-core-kit.php` - Main configuration
- `config/repositories.php` - Repository configuration
- `lang/vendor/starter-core-kit/` - Language files

---

## 🚀 Quick Start

### 1. Basic AI Usage

```php
use MkamelMasoud\StarterCoreKit\Services\Ai\AiClientService;

// AI Integration - Simple usage
$aiClient = app('ai-client');
$response = $aiClient->ask('Hello, how are you?');

// With system prompt
$aiClient->setPrompt('system', 'You are a helpful assistant');
$response = $aiClient->ask('Write a poem about coding');

// Using specific provider
$aiClient = new AiClientService('openai');
$response = $aiClient->ask('Explain Laravel');
```

### 2. Repository Pattern

```php
use MkamelMasoud\StarterCoreKit\Core\Repositories\BaseEloquentRepository;

class UserRepository extends BaseEloquentRepository
{
    protected function entity(): string
    {
        return User::class;
    }
}
```

### 3. Service Layer

```php
use MkamelMasoud\StarterCoreKit\Core\BaseService;

class UserService extends BaseService
{
    protected function getDtoClass(): string
    {
        return UserDto::class;
    }

    protected function getRepoClass(): string
    {
        return UserRepository::class;
    }
}
```

### 4. API Responses

```php
use MkamelMasoud\StarterCoreKit\Traits\Api\ApiResponsesTrait;

class UserController extends Controller
{
    use ApiResponsesTrait;

    public function index()
    {
        $users = User::all();
        return $this->success('Users retrieved successfully', $users);
    }
}
```

---

## 🔧 Configuration Options

### Main Configuration (`config/starter-core-kit.php`)

```php
return [
    'version' => '1.0.0',
    'supported_locales' => ['en', 'ar'],
    
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
        'fallback' => env('AI_PROVIDER_FALLBACK', 'router'),
        
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
];
```

### Environment Variables

```env
# AI Configuration
AI_ENABLED=true
AI_PROVIDER=openai
AI_PROVIDER_FALLBACK=router

# OpenAI Configuration
OPENAI_API_KEY=your_openai_key
OPENAI_BASEURL=https://api.openai.com
OPENAI_VERSION=v1
OPENAI_ENDPOINT=chat/completions
OPENAI_MODEL=gpt-4o-mini

# Router Configuration
ROUTER_API_KEY=your_router_key
ROUTER_BASEURL=https://openrouter.ai/api
ROUTER_VERSION=v1
ROUTER_ENDPOINT=chat/completions
ROUTER_MODEL=gpt-4o-mini

# AI Cache Configuration
AI_CACHE_ENABLED=true
AI_CACHE_TTL=600

# AI Request Configuration
AI_PROVIDER_TIMEOUT=20
AI_PROVIDER_RETRY_ATTEMPTS=2
AI_PROVIDER_RETRY_DELAY_MS=500
AI_PROVIDER_LOGGING_ENABLED=true

# Middleware Configuration
SET_LOCALE=true
CLEAR_LOGGER=false
API_CHECK_HEADERS=true

# Cache Configuration
APP_CACHE_RESULTS_ENABLED=false
APP_CACHE_RESULTS_TTL=1
```

---

## 🛠️ Advanced Usage

### Custom Exception Handling

The package provides comprehensive exception handling for common Laravel exceptions:

- `ModelNotFoundException` → 404 Not Found
- `NotFoundHttpException` → 404 Not Found
- `MethodNotAllowedHttpException` → 405 Method Not Allowed
- `ValidationException` → 422 Unprocessable Entity
- `AuthorizationException` → 401 Unauthorized
- `AuthenticationException` → 403 Forbidden
- `ThrottleRequestsException` → 429 Too Many Requests
- And many more...

### Middleware Usage

```php
// In your routes/web.php or routes/api.php
Route::middleware(['api', 'starter-core-kit.api-headers'])->group(function () {
    // Your API routes
});

Route::middleware(['starter-core-kit.locale'])->group(function () {
    // Routes that need locale detection
});
```

### Repository Advanced Features

```php
// Advanced query building
$users = $userRepository
    ->where('status', '=', 'active')
    ->where('age', '>', 18)
    ->with(['profile', 'posts'])
    ->fetchData(['status' => 'active'], 'paginate', 20);

// Search functionality
$results = $userService->search(['name' => ['John', 'Jane']]);

// Random records
$randomUsers = $userService->inRandomOrder(5);
```

### Caching Features

```php
// Automatic caching with TTL
$users = $userService->fetchData(
    filters: ['status' => 'active'],
    cachePrefix: 'active_users'
);

// Manual cache management
$userService->clearCache('users');
```

---

## 🌍 Internationalization

The package supports multiple languages out of the box:

- **English** (en) - Default
- **Arabic** (ar) - RTL support

Add more languages by extending the language files in `lang/vendor/starter-core-kit/`.

---

## 🧪 Testing

Run the test suite:

```bash
composer test
```

Run code quality checks:

```bash
composer phpstan
composer pint
```

---

## 📚 API Documentation

### AI Client Service

```php
// Basic usage
$response = app('ai-client')->ask('Your prompt here');

// With system prompt
$aiClient = app('ai-client');
$aiClient->setPrompt('system', 'You are a helpful assistant');
$response = $aiClient->ask('Your prompt');

// Using specific provider
$aiClient = new AiClientService('openai');
$response = $aiClient->ask('Your prompt');

// Advanced usage with configuration
$aiClient = new AiClientService('router');
$aiClient->setPrompt('system', 'You are a coding expert');
$response = $aiClient->ask('Explain Laravel middleware');
```

### Repository Methods

```php
// Fetch data with filters
$data = $repository->fetchData(
    filters: ['status' => 'active'],
    dataTypeReturn: 'paginate', // 'get', 'builder', 'paginate'
    limit: 20,
    random: false
);

// Find single record
$user = $repository->find(1);

// Find multiple records
$users = $repository->findMany([1, 2, 3]);

// CRUD operations
$user = $repository->store($data);
$user = $repository->update(1, $data);
$repository->delete(1);
```

### Service Layer Methods

```php
// Data fetching with caching
$users = $userService->fetchData(['status' => 'active']);

// Search functionality
$results = $userService->search(['name' => ['John']]);

// Random records
$randomUsers = $userService->inRandomOrder(5);

// CRUD with DTOs
$user = $userService->store($userDto);
$user = $userService->update(1, $userDto);
$userService->delete(1, 'soft'); // or 'force'
```

---

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🙏 Acknowledgments

- Laravel Framework
- OpenAI API
- OpenRouter API
- The Laravel community

---

## 📞 Support

- **Issues**: [GitHub Issues](https://github.com/coder0010/starter-core-kit/issues)
- **Documentation**: [GitHub Repository](https://github.com/coder0010/starter-core-kit)
- **Email**: mostafakamel000@email.com

---

**Made with ❤️ by [Mostafa Kamel](https://github.com/coder0010)**
