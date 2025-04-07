<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h4 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Survey Details') }}
            </h4>
            <div>
                <a href="{{ route('survey.index') }}">
                    <x-tertiary-button>
                        <svg class="w-3.5 h-3.5 me-1 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"/>
                        </svg>
                        {{ __('Back') }}
                    </x-tertiary-button>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">Survey Title:</h2>
                        <p class="text-gray-900 dark:text-gray-300">{{ $survey->title }}</p>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">Description:</h2>
                        <p class="text-gray-900 dark:text-gray-300">{{ $survey->description }}</p>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">Survey Model:</h2>
                        <p class="text-gray-900 dark:text-gray-300">{{ $survey->model->title }}</p>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">Status:</h2>
                        <p class="text-gray-900 dark:text-gray-300">{{ $survey->is_active ? 'Active' : 'Inactive' }}</p>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">Default Survey:</h2>
                        <p class="text-gray-900 dark:text-gray-300">{{ $survey->is_default ? 'Yes' : 'No' }}</p>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">Applies to:</h2>
                        <ul class="list-disc list-inside text-gray-900 dark:text-gray-300">
                            @foreach($survey->applies_to as $applyOption)
                                <li>{{ $applyOption }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">Targets:</h2>
                        <ul class="list-disc list-inside text-gray-900 dark:text-gray-300">
                            @foreach($survey->targets as $targetOption)
                                <li>{{ $targetOption }}</li>
                            @endforeach
                        </ul>
                    </div>


                    <!-- Questions List Table -->
                    <div class="mt-8">
                        <h2 class="text-lg font-semibold text-gray-700">Questions</h2>
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Question</th>
                                    <th scope="col" class="px-6 py-3">Type</th>
                                    <th scope="col" class="px-6 py-3">Points</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($survey->questions as $question)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ $question->question }}
                                        </td>
                                        <td class="px-6 py-4">{{ $question->type->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ $question->points }}</td>
                                        <td class="px-6 py-4">{{ $question->is_active ? 'Active' : 'Inactive' }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('question.show', $question->id) }}" class="text-indigo-600 hover:text-indigo-900 pr-3">View</a>
                                            <a href="{{ route('question.edit', $question->id) }}" class="text-green-600 hover:text-green-900 pr-3">Edit</a>
                                            <form action="{{ route('question.destroy', $question->id) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this question?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No questions found.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div class="flex items-center justify-between mt-6">
                        <div>
                            <a href="{{route('question.create',['survey_id' => $survey->id])}}" class="pr-3"><x-secondary-button>{{ __('Add Question') }}</x-secondary-button></a>
                        </div>
                        <div class="flex">
                        <a href="{{ route('survey.edit',$survey->id ) }}" class="pr-3"><x-primary-button>{{ __('Edit') }}</x-primary-button></a>
                        <form action="{{ route('survey.destroy', $survey->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this survey?') }}');">
                            @csrf
                            @method('DELETE')
                            <x-danger-button>
                                {{ __('Delete') }}
                            </x-danger-button>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
