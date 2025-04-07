<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h4 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Question Details') }}
            </h4>
            <div>
                <a href="{{ route('question.index') }}">
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
                    <h2 class="text-lg font-semibold text-gray-700">Survey : {{ $question->survey->title ?? 'N/A' }}</h2>
                    <div class="mt-4">
                        <strong>Question:</strong>
                        <p class="text-gray-800 dark:text-gray-300">{{ $question->question }}</p>
                    </div>

                    <div class="mt-4">
                        <strong>Description:</strong>
                        <p class="text-gray-800 dark:text-gray-300">{{ $question->description }}</p>
                    </div>

                    <div class="mt-4">
                        <strong>Type:</strong>
                        <p class="text-gray-800 dark:text-gray-300">{{ $question->type->name ?? 'N/A' }}</p>
                    </div>

                    <div class="mt-4">
                        <strong>Points:</strong>
                        <p class="text-gray-800 dark:text-gray-300">{{ $question->points }}</p>
                    </div>

                    <div class="mt-4">
                        <strong>Status:</strong>
                        <p class="text-gray-800 dark:text-gray-300">{{ $question->is_active ? 'Active' : 'Inactive' }}</p>
                    </div>
                    <div class="mt-4">
                        <strong>Created At:</strong>
                        <p class="text-gray-800 dark:text-gray-300">{{ $question->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div class="mt-4">
                        <strong>Updated At:</strong>
                        <p class="text-gray-800 dark:text-gray-300">{{ $question->updated_at->format('d M Y H:i') }}</p>
                    </div>
                    <div class="mt-4">
                        <strong>Options</strong>
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($question->options as $option)
                                <li>{{ $option->name }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mt-4">
                        <strong>Users Answered:</strong>
                        <p class="text-gray-800 dark:text-gray-300">{{ $question->usersSurveysRates->count() }}</p>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('question.edit', $question->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">{{ __('Edit') }}</a>
                        <form action="{{ route('question.destroy', $question->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this question?') }}');">
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
</x-app-layout>
