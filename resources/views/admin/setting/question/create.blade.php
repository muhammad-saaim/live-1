<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h4 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create New Question') }}
            </h4>
            <div>
                <a href="{{ route('question.index') }}">
                    <x-tertiary-button>
                        <svg class="w-3.5 h-3.5 me-1 rtl:rotate-180" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 5H1m0 0 4 4M1 5l4-4" />
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
                    @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('question.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="survey_id" value="{{ $survey_id }}">
                        <div class="mb-4">
                            <label for="question" class="block text-sm font-medium text-gray-700">Question</label>
                            <textarea name="question" id="question" rows="3" maxlength="250" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300
                                            focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('question') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300
                                focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{old('description')}}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="type_id" class="block text-sm font-medium text-gray-700">Type</label>
                            <select name="type_id" id="type_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Select a type</option>
                                @foreach($types as $type)
                                <option value="{{ $type->id }}" {{ old('type_id')==$type->id ? 'selected' : '' }}>{{
                                    $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="points" class="block text-sm font-medium text-gray-700">Points</label>
                            <input type="number" name="points" id="points" value="{{ old('points', 1) }}" min="1"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div class="mb-4">
                            <label for="is_active" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="is_active" id="is_active" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="1" {{ old('is_active', 1)==1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active', 1)==0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="reverse_score" class="block text-sm font-medium text-gray-700">Reverse
                                Score</label>
                            <select name="reverse_score" id="reverse_score" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="1" {{ old('reverse_score', 0)==1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('reverse_score', 0)==0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('question.index') }}" class="text-gray-600 hover:text-gray-900">{{
                                __('Cancel') }}</a>
                            <x-primary-button type="submit" class="ml-4">
                                {{ __('Create Question') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>