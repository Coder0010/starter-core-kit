<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Starter Core Kit for Laravel - Demo & Documentation</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
    <style>
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .feature-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .feature-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
        .code-block { background: #2d3748; border-radius: 8px; }
        .tab-button { transition: all 0.3s ease; }
        .tab-button.active { background: #4f46e5; color: white; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Hero Section -->
    <div class="gradient-bg text-white py-16">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center">
                <h1 class="text-5xl font-extrabold mb-6">🚀 Starter Core Kit</h1>
                <p class="text-xl mb-8 text-blue-100">A comprehensive, production-ready Laravel starter package</p>
                <div class="flex justify-center space-x-4">
                    <span class="bg-white bg-opacity-20 px-4 py-2 rounded-full text-sm">v1.2.0</span>
                    <span class="bg-white bg-opacity-20 px-4 py-2 rounded-full text-sm">Laravel 8+</span>
                    <span class="bg-white bg-opacity-20 px-4 py-2 rounded-full text-sm">PHP 8.1+</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-6 py-12">
        <!-- Features Grid -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">✨ Key Features</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="feature-card bg-white rounded-lg p-6 shadow-lg">
                    <div class="text-3xl mb-4">🛡️</div>
                    <h3 class="text-xl font-semibold mb-3">Exception Handling</h3>
                    <p class="text-gray-600">Custom global exception handler with JSON responses and multi-language support</p>
                </div>
                <div class="feature-card bg-white rounded-lg p-6 shadow-lg">
                    <div class="text-3xl mb-4">🤖</div>
                    <h3 class="text-xl font-semibold mb-3">AI Integration</h3>
                    <p class="text-gray-600">Multi-provider AI support with OpenAI and Router.ai integration</p>
                </div>
                <div class="feature-card bg-white rounded-lg p-6 shadow-lg">
                    <div class="text-3xl mb-4">🏗️</div>
                    <h3 class="text-xl font-semibold mb-3">Repository Pattern</h3>
                    <p class="text-gray-600">Base repository and service layer with caching and transaction support</p>
                </div>
                <div class="feature-card bg-white rounded-lg p-6 shadow-lg">
                    <div class="text-3xl mb-4">🔧</div>
                    <h3 class="text-xl font-semibold mb-3">Middleware</h3>
                    <p class="text-gray-600">API header validation, locale detection, and custom middleware</p>
                </div>
                <div class="feature-card bg-white rounded-lg p-6 shadow-lg">
                    <div class="text-3xl mb-4">📊</div>
                    <h3 class="text-xl font-semibold mb-3">Data Management</h3>
                    <p class="text-gray-600">Advanced query building, pagination, search, and caching</p>
                </div>
                <div class="feature-card bg-white rounded-lg p-6 shadow-lg">
                    <div class="text-3xl mb-4">🌍</div>
                    <h3 class="text-xl font-semibold mb-3">Internationalization</h3>
                    <p class="text-gray-600">Multi-language support with English and Arabic translations</p>
                </div>
            </div>
        </div>

        <!-- Installation & Quick Start -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">🚀 Quick Start</h2>
            
            <!-- Tabs -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8 px-6">
                        <button class="tab-button py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 active" onclick="showTab('install')">
                            Installation
                        </button>
                        <button class="tab-button py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700" onclick="showTab('config')">
                            Configuration
                        </button>
                        <button class="tab-button py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700" onclick="showTab('usage')">
                            Usage Examples
                        </button>
                        <button class="tab-button py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700" onclick="showTab('api')">
                            API Reference
                        </button>
                    </nav>
                </div>

                <!-- Installation Tab -->
                <div id="install" class="tab-content p-6">
                    <h3 class="text-xl font-semibold mb-4">Installation</h3>
                    <div class="code-block p-4 mb-4">
                        <pre><code class="language-bash">composer require mkamelmasoud/starter-core-kit</code></pre>
                    </div>
                    <p class="text-gray-600 mb-4">The package will automatically register its service provider.</p>
                    
                    <h4 class="text-lg font-semibold mb-3">Publish Configuration</h4>
                    <div class="code-block p-4">
                        <pre><code class="language-bash">php artisan vendor:publish --tag=starter-core-kit</code></pre>
                    </div>
                </div>

                <!-- Configuration Tab -->
                <div id="config" class="tab-content p-6" style="display: none;">
                    <h3 class="text-xl font-semibold mb-4">Environment Variables</h3>
                    <div class="code-block p-4 mb-6">
                        <pre><code class="language-env"># AI Configuration
AI_PROVIDER=router
OPENAI_API_KEY=your_openai_key
ROUTER_API_KEY=your_router_key

# Middleware Configuration
SET_LOCALE=true
CLEAR_LOGGER=false
API_CHECK_HEADERS=true

# Cache Configuration
APP_CACHE_RESULTS_ENABLED=false
APP_CACHE_RESULTS_TTL=1</code></pre>
                    </div>
                    
                    <h4 class="text-lg font-semibold mb-3">Main Configuration</h4>
                    <div class="code-block p-4">
                        <pre><code class="language-php">// config/starter-core-kit.php
return [
    'version' => '1.0.0',
    'supported_locales' => ['en', 'ar'],
    
    'middlewares' => [
        'set_locale' => env('SET_LOCALE', true),
        'clear_logger' => env('CLEAR_LOGGER', false),
        'api_check_headers' => env('API_CHECK_HEADERS', true),
    ],
    
    'ai' => [
        'default' => env('AI_PROVIDER', 'router'),
        'providers' => [
            'openai' => [
                'apikey' => env('OPENAI_API_KEY'),
                'endpoint' => env('OPENAI_ENDPOINT'),
                'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
            ],
        ],
    ],
];</code></pre>
                    </div>
                </div>

                <!-- Usage Examples Tab -->
                <div id="usage" class="tab-content p-6" style="display: none;">
                    <h3 class="text-xl font-semibold mb-4">Usage Examples</h3>
                    
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-3">AI Integration</h4>
                        <div class="code-block p-4">
                            <pre><code class="language-php">use MkamelMasoud\StarterCoreKit\Services\Ai\AiClientService;

// Basic AI usage
$aiClient = app('ai-client');
$response = $aiClient->ask('Hello, how are you?');

// With system prompt
$response = $aiClient->ask('Write a poem', [
    'system_prompt' => 'You are a creative poet'
]);</code></pre>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-3">Repository Pattern</h4>
                        <div class="code-block p-4">
                            <pre><code class="language-php">use MkamelMasoud\StarterCoreKit\Core\Repositories\BaseEloquentRepository;

class UserRepository extends BaseEloquentRepository
{
    protected function entity(): string
    {
        return User::class;
    }
}

// Usage
$users = $userRepository->fetchData(['status' => 'active'], 'paginate', 20);</code></pre>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-3">Service Layer</h4>
                        <div class="code-block p-4">
                            <pre><code class="language-php">use MkamelMasoud\StarterCoreKit\Core\BaseService;

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

// Usage
$users = $userService->fetchData(['status' => 'active']);
$user = $userService->store($userDto);</code></pre>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-3">API Responses</h4>
                        <div class="code-block p-4">
                            <pre><code class="language-php">use MkamelMasoud\StarterCoreKit\Traits\Api\ApiResponsesTrait;

class UserController extends Controller
{
    use ApiResponsesTrait;

    public function index()
    {
        $users = User::all();
        return $this->success('Users retrieved successfully', $users);
    }
}</code></pre>
                        </div>
                    </div>
                </div>

                <!-- API Reference Tab -->
                <div id="api" class="tab-content p-6" style="display: none;">
                    <h3 class="text-xl font-semibold mb-4">API Reference</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-lg font-semibold mb-3">AI Client Service</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 mb-2"><strong>Method:</strong> ask(string $prompt, array $options = [])</p>
                                <p class="text-sm text-gray-600 mb-2"><strong>Returns:</strong> string|null</p>
                                <p class="text-sm text-gray-600"><strong>Description:</strong> Send a prompt to the AI provider and return the generated text.</p>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-lg font-semibold mb-3">Repository Methods</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li><strong>fetchData()</strong> - Fetch records with filters and return type control</li>
                                    <li><strong>find($id)</strong> - Find single record by ID</li>
                                    <li><strong>findMany($ids)</strong> - Find multiple records by IDs</li>
                                    <li><strong>store($data)</strong> - Create new record</li>
                                    <li><strong>update($id, $data)</strong> - Update existing record</li>
                                    <li><strong>delete($id)</strong> - Delete record</li>
                                </ul>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-lg font-semibold mb-3">Service Layer Methods</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li><strong>fetchData()</strong> - Data fetching with caching</li>
                                    <li><strong>search()</strong> - Advanced search functionality</li>
                                    <li><strong>inRandomOrder()</strong> - Get random records</li>
                                    <li><strong>store($dto)</strong> - Store with DTO validation</li>
                                    <li><strong>update($id, $dto)</strong> - Update with DTO validation</li>
                                    <li><strong>delete($id, $type)</strong> - Delete with soft/force options</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exception Handling Demo -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">🛡️ Exception Handling</h2>
            <div class="bg-white rounded-lg shadow-lg p-6">
                <p class="text-gray-600 mb-4">The package provides comprehensive exception handling for common Laravel exceptions:</p>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            <span class="text-sm">ModelNotFoundException → 404 Not Found</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            <span class="text-sm">ValidationException → 422 Unprocessable Entity</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            <span class="text-sm">AuthorizationException → 401 Unauthorized</span>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            <span class="text-sm">AuthenticationException → 403 Forbidden</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            <span class="text-sm">ThrottleRequestsException → 429 Too Many Requests</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            <span class="text-sm">And many more...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Comparison -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">📊 Package Features</h2>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Feature</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Exception Handling</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Custom global exception handler with JSON responses</td>
                            <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">✅ Ready</span></td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">AI Integration</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Multi-provider AI support (OpenAI, Router.ai)</td>
                            <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">✅ Ready</span></td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Repository Pattern</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Base repository and service layer implementation</td>
                            <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">✅ Ready</span></td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Middleware</td>
                            <td class="px-6 py-4 text-sm text-gray-500">API header validation and locale detection</td>
                            <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">✅ Ready</span></td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Caching</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Built-in caching with TTL configuration</td>
                            <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">✅ Ready</span></td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Internationalization</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Multi-language support (English/Arabic)</td>
                            <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">✅ Ready</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Ready to Get Started?</h3>
            <p class="text-gray-600 mb-6">Install the package and start building amazing Laravel applications with our comprehensive starter kit.</p>
            <div class="flex justify-center space-x-4">
                <a href="https://github.com/mkamelmasoud/starter-core-kit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                    View on GitHub
                </a>
                <a href="https://packagist.org/packages/mkamelmasoud/starter-core-kit" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors">
                    View on Packagist
                </a>
            </div>
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-gray-500 text-sm">This package is open-sourced software licensed under the MIT license.</p>
                <p class="text-gray-500 text-sm mt-2">Made with ❤️ by <a href="https://github.com/mkamelmasoud" class="text-indigo-600 hover:underline">Mostafa Kamel</a></p>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => {
                content.style.display = 'none';
            });

            // Remove active class from all tab buttons
            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(button => {
                button.classList.remove('active');
            });

            // Show selected tab content
            document.getElementById(tabName).style.display = 'block';

            // Add active class to clicked tab button
            event.target.classList.add('active');
        }
    </script>
</body>
</html>
