<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h4 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Surveys') }}
            </h4>
            <div>
                <a href="{{ route('admin.index') }}">
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
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <!-- Header Section -->
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">Survey List</h2>
                                <p class="text-gray-600 text-sm">A list of all available surveys.</p>
                            </div>
                            <a href="{{ route('survey.create') }}">
                                <x-primary-button>{{ __('New Survey') }}</x-primary-button>
                            </a>
                        </div>

                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Title</th>
                                <th scope="col" class="px-6 py-3">Description</th>
                                <th scope="col" class="px-6 py-3">Model</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Questions</th>
                                <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($surveys as $survey)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <a href="{{ route('survey.show', $survey->id) }}">
                                            {{ $survey->title }}
                                        </a>
                                    </th>
                                    <td class="px-6 py-4">{{ $survey->description }}</td>
                                    <td class="px-6 py-4">{{ $survey->model->title ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        {{ $survey->is_active ? 'Active' : 'Inactive' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $survey->questions()->count() }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('survey.show', $survey->id) }}">
                                            <x-tertiary-button>View</x-tertiary-button>
                                        </a>
                                        <a href="{{ route('survey.edit', $survey->id) }}">
                                            <x-secondary-button>Edit</x-secondary-button>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td colspan="5" class="text-center py-4">No surveys found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
