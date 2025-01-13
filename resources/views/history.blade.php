<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-2xl font-semibold mb-4">Your Disease Detection History</h2>
                    @if ($diseaseDetections->isEmpty())
                        <p>You have no disease detection history.</p>
                    @else
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Image</th>
                                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Disease Name</th>
                                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Description</th>
                                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Remedy</th>
                                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Other Recommendations</th>
                                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($diseaseDetections as $detection)
                                    <tr>
                                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">
                                            <img src="{{ asset('storage/' . $detection->image_path) }}" alt="Image" class="w-20 h-20 object-cover">
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $detection->disease_name }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $detection->description }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $detection->remedy }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $detection->other_recommendations }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ $detection->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>