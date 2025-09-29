<x-app-layout>
    <x-slot name="header">
        <h4 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight pb-2">
            {{ __('Admin console') }}
        </h4>
        <a href="{{ route('surveyModel.index') }}"><x-tertiary-button>Model</x-tertiary-button></a>
        <a href="{{ route('group.index') }}"><x-tertiary-button>User group</x-tertiary-button></a>
        <a href="{{ route('survey.index') }}"><x-tertiary-button>Survey</x-tertiary-button></a>
        <a href="{{ route('question.index') }}"><x-tertiary-button>Question</x-tertiary-button></a>
        <a href="{{ route('users.index') }}"><x-tertiary-button>Users</x-tertiary-button></a>
        <a href="{{ route('services.index') }}"><x-tertiary-button>Services</x-tertiary-button></a>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Admin console content -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
