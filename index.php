<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Error {{ $status }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-6">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8 text-center">
        <h1 class="text-6xl font-extrabold text-red-600 mb-4">Error {{ $status }}</h1>
        <p class="text-lg text-gray-700">{{ $message }}</p>
    </div>
</body>
</html>
