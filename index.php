<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Starter Core Kit for Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-8">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-extrabold text-indigo-600 mb-4">🚀 Starter Core Kit for Laravel</h1>
            <p class="text-lg text-gray-700">A lightweight, extendable Laravel starter package</p>
        </div>

        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Features</h2>
            <ul class="list-disc pl-6 space-y-2">
                <li>✅ Custom global exception handling</li>
                <li>✅ API request header validation middleware</li>
                <li>✅ Dynamic locale middleware</li>
                <li>✅ Exception messages localization (multi-language support)</li>
                <li>✅ Exception logging to the database (optional, via migration publishing)</li>
            </ul>
        </div>

        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Installation</h2>
            <div class="bg-gray-100 p-4 rounded-md">
                <code class="text-sm">composer require mkamel/starter-core-kit</code>
            </div>
            <p class="mt-4 text-gray-700">After installation, the package will automatically register its service provider.</p>
        </div>

        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Configuration</h2>
            <p class="mb-4 text-gray-700">Publish the configuration file and language files:</p>
            <div class="bg-gray-100 p-4 rounded-md">
                <code class="text-sm">php artisan vendor:publish --tag=starter-core-kit</code>
            </div>
        </div>

        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Documentation</h2>
            <p class="text-gray-700">For complete documentation, please refer to the <a href="https://github.com/mkamel/starter-core-kit" class="text-indigo-600 hover:underline">GitHub repository</a> or check the README.md file included with this package.</p>
        </div>

        <div class="border-t pt-6 text-center text-gray-600 text-sm">
            <p>This package is open-sourced software licensed under the MIT license.</p>
        </div>
    </div>
</body>
</html>
