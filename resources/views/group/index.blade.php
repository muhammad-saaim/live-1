<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h4 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Groups') }}
            </h4>
            <div>
                <a href="{{ route('dashboard.index') }}">
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

    <!--bg-ml-color-lime-->
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
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <!-- Header Section -->
                                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                                    <div>
                                        <h2 class="text-xl font-semibold text-gray-800">Groups</h2>
                                        <p class="text-gray-600 text-sm">A list of all the groups.</p>
                                    </div>
                                    <a href="{{ route('group.create') }}">
                                        <x-primary-button>{{ __('New Group') }}</x-primary-button>
                                    </a>

                                </div>
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Group name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Description
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Total Users
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($groups as $group)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            <a href="{{ route('group.show', $group->id) }}">
                                            {{ $group->name }}</a>
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ $group->description }}</td>
                                        <td class="px-6 py-4">
                                            {{ $group->users()->count() }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('group.show', $group->id) }}">
                                                <x-ghost-button>View</x-ghost-button></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="4" class="text-center">No groups found.</td>
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

