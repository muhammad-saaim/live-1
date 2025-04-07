<x-app-layout>
    <x-slot name="header">
        <h4 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Group Details') }}
        </h4>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-4">
                        <h2 class="text-2xl font-semibold text-gray-800">{{ $surveyModel->title }}</h2>
                        <p class="mt-2 text-gray-600">{{ $surveyModel->description }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="mt-2 text-gray-600">User(s): {{ $surveyModel->surveys()->count() }}</p>
                    </div>
                    <div class="mt-6 text-sm text-gray-500">
                        <span>Created on: {{ $surveyModel->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('surveyModel.index') }}" class="text-blue-500 hover:underline">Back to Models</a>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-xl font-semibold text-gray-800">Surveys</h3>
                        <table class="min-w-full mt-4 bg-white border border-gray-200 rounded-lg">
                            <thead>
                            <tr class="bg-gray-100 border-b">
                                <th class="px-4 py-2 text-left text-gray-600">#</th>
                                <th class="px-4 py-2 text-left text-gray-600">Title</th>
                                <th class="px-4 py-2 text-left text-gray-600">Description</th>
                                <th class="px-4 py-2 text-left text-gray-600">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($surveyModel->surveys as $survey)
                                <tr class="border-b">
                                    <td class="px-4 py-2 text-gray-700">{{ $survey->iteration }}</td>
                                    <td class="px-4 py-2 text-gray-700">{{ $survey->title }}</td>
                                    <td class="px-4 py-2 text-gray-700">{{ $survey->description }}</td>
                                    <td class="px-4 py-2 text-gray-700">{{ $survey->is_active }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex space-x-4">
                        <a href="{{ route('surveyModel.edit', $surveyModel->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit
                        </a>
                        <form action="{{ route('surveyModel.destroy', $surveyModel->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this group?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Delete
                            </button>
                        </form>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('surveyModel.index') }}" class="text-blue-500 hover:underline">Back to Models</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
