<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h4 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Users') }}
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
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Search Form -->
                    <div class="mb-4 flex justify-between items-center">
                        <form action="{{ route('users.index') }}" method="GET" class="flex items-center">
                            <input type="text" name="search" placeholder="Search users..." value="{{ request('search') }}"
                                   class="border border-gray-300 rounded-md py-2 px-4 focus:outline-none focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <button type="submit" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:bg-blue-700">
                                Search
                            </button>
                        </form>
                        <a href="{{ route('users.create') }}">
                            <x-primary-button>{{ __('New User') }}</x-primary-button>
                        </a>
                    </div>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <!-- Header Section -->
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">User List</h2>
                                <p class="text-gray-600 text-sm">A list of all registered users.</p>
                            </div>
                        </div>

                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Name</th>
                                <th scope="col" class="px-6 py-3">Username</th>
                                <th scope="col" class="px-6 py-3">Role</th>
                                <th scope="col" class="px-6 py-3">Email</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Registered</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($users as $user)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <a href="{{ route('users.show', $user->id) }}">
                                            {{ $user->name }}
                                        </a>
                                    </th>
                                    <td class="px-6 py-4">{{ $user->username ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ $user->roles->pluck('name')->join(', ') }}</td>
                                    <td class="px-6 py-4">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        {{ $user->status ? 'Active' : 'Inactive' }}
                                    </td>
                                    <td class="px-6 py-4">{{ $user->created_at }}</td>
                                </tr>
                            @empty
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td colspan="6" class="text-center py-4">No users found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination Links -->
                        <div class="m-4 flex justify-end">
                            {{ $users->appends(['search' => request('search')])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
