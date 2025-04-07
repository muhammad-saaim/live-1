<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h4 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create New Survey') }}
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
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('survey.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="model_id" class="block text-sm font-medium text-gray-700">Survey Model</label>
                            <select name="model_id" id="model_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Select a model</option>
                                @foreach($surveyModels as $model)
                                    <option value="{{ $model->id }}" {{ old('model_id') == $model->id ? 'selected' : '' }}>{{ $model->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="is_active" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="is_active" id="is_active" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active', 1) == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="is_default" class="block text-sm font-medium text-gray-700">Default Survey</label>
                            <select name="is_default" id="is_default" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="0" {{ old('is_default', 0) == 0 ? 'selected' : '' }}>No</option>
                                <option value="1" {{ old('is_default', 0) == 1 ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Applies to:</label>
                            <div class="flex items-center space-x-4 mt-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="applies_to[]" value="Individual" {{ is_array(old('applies_to')) && in_array('Individual', old('applies_to')) ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-green-600">
                                    <span class="ml-2">Individual</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="applies_to[]" value="Group" {{ is_array(old('applies_to')) && in_array('Group', old('applies_to')) ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-green-600">
                                    <span class="ml-2">Group</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="applies_to[]" value="Family" {{ is_array(old('applies_to')) && in_array('Family', old('applies_to')) ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-green-600">
                                    <span class="ml-2">Family</span>
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Targets:</label>
                            <div class="flex flex-col space-y-2 mt-2">
                                <!-- Checkbox for "User is evaluator" -->
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="targets[]" value="User is evaluator" {{ is_array(old('targets')) && in_array('User is evaluator', old('targets')) ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-green-600">
                                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">User is evaluator</span>
                                </label>

                                <!-- Checkbox for "Admin is evaluator" -->
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="targets[]" value="Admin is evaluator" {{ is_array(old('targets')) && in_array('Admin is evaluator', old('targets')) ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-green-600">
                                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Admin is evaluator</span>
                                </label>

                                <!-- Checkbox for "User is evaluatee" -->
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="targets[]" value="User is evaluatee" {{ is_array(old('targets')) && in_array('User is evaluatee', old('targets')) ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-green-600">
                                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">User is evaluatee</span>
                                </label>

                                <!-- Checkbox for "Admin is evaluatee" -->
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="targets[]" value="Admin is evaluatee" {{ is_array(old('targets')) && in_array('Admin is evaluatee', old('targets')) ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-green-600">
                                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Admin is evaluatee</span>
                                </label>
                            </div>
                        </div>



                        <div class="flex items-center justify-end">
                            <a href="{{ route('survey.index') }}" class="text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
                            <x-primary-button type="submit" class="ml-4">
                                {{ __('Create Survey') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
