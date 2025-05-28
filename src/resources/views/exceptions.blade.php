<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Exceptions List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Tailwind CSS CDN -->
    @csrf
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <div class="max-w-5xl mx-auto">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Exception Messages</h1>
            <button onclick="openModal()"
                class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                + Add Exception
            </button>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Table -->
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full table-auto border-collapse">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="border px-4 py-2 text-left text-gray-700">Exception Class</th>
                        <th class="border px-4 py-2 text-left text-gray-700">Message (EN)</th>
                        <th class="border px-4 py-2 text-left text-gray-700">Message (AR)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($exceptions as $exception)
                        <tr>
                            <td class="border px-4 py-2">{{ $exception->exception_class }}</td>
                            <td class="border px-4 py-2 whitespace-pre-wrap">
                                {!! nl2br(e('    ' . $exception->message['en'])) !!}
                            </td>
                            <td class="border px-4 py-2 whitespace-pre-wrap">
                                {!! nl2br(e('    ' . $exception->message['ar'])) !!}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-gray-500 py-4">No data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            {{ $exceptions->onEachSide(1)->links('pagination::tailwind') }}
        </div>

    </div>

    <!-- Modal -->
    <div id="formModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg relative">

            <button onclick="closeModal()"
                class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>

            <h2 class="text-xl font-bold mb-6 text-center text-gray-800">Add New Exception</h2>

            <!-- Form -->
            <form action="{{ route('exceptions.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700">Exception Class</label>
                    <input name="exception_class" type="text" required
                        class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Message (English)</label>
                    <textarea name="message[en]" rows="3" required
                        class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 whitespace-pre-wrap"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Message (Arabic)</label>
                    <textarea name="message[ar]" rows="3" required
                        class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 whitespace-pre-wrap"></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-100">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Save</button>
                </div>

            </form>
        </div>
    </div>

    <!-- Simple native JS for modal toggling -->
    <script>
        function openModal() {
            document.getElementById('formModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('formModal').classList.add('hidden');
        }
    </script>

</body>
</html>
