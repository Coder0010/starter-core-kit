<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Starter Core Kit for Laravel - Demo & Docs</title>

    <!-- ✅ Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>

    <style>
        /* Hero gradient animation */
        @keyframes gradientFlow {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        .animated-gradient {
            background: linear-gradient(120deg, #6366f1, #8b5cf6, #ec4899);
            background-size: 200% 200%;
            animation: gradientFlow 10s ease infinite;
        }

        .glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            transition: all 0.3s ease;
        }
        .glass:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -6px rgba(0,0,0,0.1);
        }

        .tab-content { display: none; }
        .tab-content.active { display: block; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .tab-button.active {
            background: linear-gradient(90deg, #4f46e5, #7c3aed);
            color: #fff;
            border-radius: .375rem;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

<!-- Hero -->
<header class="animated-gradient text-white py-24 relative overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="max-w-6xl mx-auto px-6 relative z-10 text-center">
        <h1 class="text-6xl font-extrabold mb-4 tracking-tight drop-shadow-lg">Starter Core Kit</h1>
        <p class="text-lg text-blue-100 mb-8">Production-ready Laravel starter for modern backend developers</p>
        <div class="flex justify-center gap-3 flex-wrap text-sm">
            <span class="bg-white/20 px-4 py-2 rounded-full">v1.2.0</span>
            <span class="bg-white/20 px-4 py-2 rounded-full">Laravel 8+</span>
            <span class="bg-white/20 px-4 py-2 rounded-full">PHP 8.1+</span>
        </div>
    </div>
</header>

<!-- Features -->
<section class="py-20">
    <div class="max-w-6xl mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold mb-10">✨ Key Features</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="glass rounded-xl p-8 shadow-sm">
                <div class="text-4xl mb-3 text-indigo-600">🛡️</div>
                <h3 class="text-xl font-semibold mb-2">Exception Handling</h3>
                <p class="text-gray-600 text-sm">Custom handler with JSON responses and multilingual support.</p>
            </div>
            <div class="glass rounded-xl p-8 shadow-sm">
                <div class="text-4xl mb-3 text-indigo-600">🤖</div>
                <h3 class="text-xl font-semibold mb-2">AI Integration</h3>
                <p class="text-gray-600 text-sm">Seamless OpenAI & Router.ai integration.</p>
            </div>
            <div class="glass rounded-xl p-8 shadow-sm">
                <div class="text-4xl mb-3 text-indigo-600">🏗️</div>
                <h3 class="text-xl font-semibold mb-2">Repository Pattern</h3>
                <p class="text-gray-600 text-sm">Base repository with caching & transaction support.</p>
            </div>
            <div class="glass rounded-xl p-8 shadow-sm">
                <div class="text-4xl mb-3 text-indigo-600">🔧</div>
                <h3 class="text-xl font-semibold mb-2">Middleware</h3>
                <p class="text-gray-600 text-sm">API headers, locale detection, and custom logic.</p>
            </div>
            <div class="glass rounded-xl p-8 shadow-sm">
                <div class="text-4xl mb-3 text-indigo-600">📊</div>
                <h3 class="text-xl font-semibold mb-2">Data Management</h3>
                <p class="text-gray-600 text-sm">Query building, pagination, caching, and more.</p>
            </div>
            <div class="glass rounded-xl p-8 shadow-sm">
                <div class="text-4xl mb-3 text-indigo-600">🌍</div>
                <h3 class="text-xl font-semibold mb-2">Internationalization</h3>
                <p class="text-gray-600 text-sm">Built-in English & Arabic translation support.</p>
            </div>
        </div>
    </div>
</section>

<!-- Quick Start Tabs -->
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        <h2 class="text-3xl font-bold text-center mb-10">🚀 Quick Start</h2>

        <div class="border border-gray-200 rounded-xl shadow-md overflow-hidden">
            <nav class="flex flex-wrap bg-gray-50 border-b border-gray-200 p-2">
                <button class="tab-button active px-4 py-2" onclick="showTab(event, 'install')">Installation</button>
                <button class="tab-button px-4 py-2" onclick="showTab(event, 'config')">Configuration</button>
                <button class="tab-button px-4 py-2" onclick="showTab(event, 'usage')">Usage</button>
            </nav>

            <div id="install" class="tab-content active p-6">
                <p class="mb-4 font-semibold">Run this command to install:</p>
                <div class="bg-gray-900 text-gray-100 rounded-lg p-4 text-sm">
                    <pre><code class="language-bash">composer require mkamelmasoud/starter-core-kit</code></pre>
                </div>
            </div>

            <div id="config" class="tab-content p-6">
                <p class="mb-4 font-semibold">Publish configuration:</p>
                <div class="bg-gray-900 text-gray-100 rounded-lg p-4 text-sm">
                    <pre><code class="language-bash">php artisan vendor:publish --tag=starter-core-kit</code></pre>
                </div>
            </div>

            <div id="usage" class="tab-content p-6">
                <p class="mb-4 font-semibold">Example usage:</p>
                <div class="bg-gray-900 text-gray-100 rounded-lg p-4 text-sm">
            <pre><code class="language-php">// Resolve the AI client from the container
$aiClient = app('ai-client');

// Optional: add a system prompt to guide responses
$aiClient->setPrompt('system', 'You are a helpful assistant.');

// Ask the AI
$response = $aiClient->ask('Hello, how are you?');

// Or use a specific provider
// $aiClient = new \MkamelMasoud\StarterCoreKit\Services\Ai\AiClientService('openai');
// $response = $aiClient->ask('Explain Laravel middleware in one paragraph.');</code></pre>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="py-16 bg-gray-900 text-gray-300 text-center">
    <div class="max-w-4xl mx-auto px-6">
        <h3 class="text-2xl font-bold text-white mb-4">Ready to Get Started?</h3>
        <p class="text-gray-400 mb-8">Install the package and start building powerful Laravel backends.</p>
        <div class="flex justify-center gap-4 mb-8">
            <a href="https://github.com/mkamelmasoud/starter-core-kit"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition">
                GitHub
            </a>
            <a href="https://packagist.org/packages/mkamelmasoud/starter-core-kit"
               class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition">
                Packagist
            </a>
        </div>
        <p class="text-sm text-gray-500">Made with ❤️ by
            <a href="https://github.com/mkamelmasoud" class="text-indigo-400 hover:underline">Mostafa Kamel</a>.
        </p>
    </div>
</footer>

<script>
    function showTab(event, id) {
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        document.getElementById(id).classList.add('active');
        event.target.classList.add('active');
    }
</script>
</body>
</html>
