<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h4 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('User Details') }}
            </h4>
            <div>
                <a href="{{ route('users.index') }}">
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
                    <!-- User Details -->
                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">Name:</h2>
                        <p class="text-gray-900 dark:text-gray-300">{{ $user->name }}</p>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">Username:</h2>
                        <p class="text-gray-900 dark:text-gray-300">{{ $user->username ?? 'N/A' }}</p>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">Email:</h2>
                        <p class="text-gray-900 dark:text-gray-300">{{ $user->email }}</p>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">Roles:</h2>
                        <p class="text-gray-900 dark:text-gray-300">
                            {{ $user->roles->pluck('name')->join(', ') }}
                        </p>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">Status:</h2>
                        <p class="text-gray-900 dark:text-gray-300">{{ $user->status ? 'Active' : 'Inactive' }}</p>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">Phone:</h2>
                        <p class="text-gray-900 dark:text-gray-300">{{ $user->phone ?? 'N/A' }}</p>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">Country:</h2>
                        <p class="text-gray-900 dark:text-gray-300">{{ $country_name ?? 'N/A' }}</p>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">Language:</h2>
                        <p class="text-gray-900 dark:text-gray-300">{{ $user->language ?? 'N/A' }}</p>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">Registered At:</h2>
                        <p class="text-gray-900 dark:text-gray-300">{{ $user->created_at->format('F j, Y, g:i a') }}</p>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">Updated At:</h2>
                        <p class="text-gray-900 dark:text-gray-300">{{ $user->updated_at->format('F j, Y, g:i a') }}</p>
                    </div>

                    <!-- Edit and Delete Actions -->
                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('users.edit', $user->id) }}" class="pr-3">
                            <x-primary-button>{{ __('Edit') }}</x-primary-button>
                        </a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this user?') }}');">
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
