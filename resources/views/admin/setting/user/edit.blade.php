<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h4 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit User') }}
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
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div class="mb-4">
                            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                            <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <p class="text-sm text-gray-500 mt-1">Leave blank if you don't want to change the password.</p>
                        </div>

                        @if(isset($roles))
                            <div class="mb-4">
                                <label for="roles" class="block text-sm font-medium text-gray-700">Roles</label>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach($roles as $role)
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" {{ $user->roles->contains($role->id) ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-indigo-600">
                                            <span class="ml-2 text-gray-700">{{ $role->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $user->status) == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        @if(isset($countries))
                            <div class="mb-4">
                                <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                                <select name="country" id="country" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Select a country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ old('country', $user->country) == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label for="language" class="block text-sm font-medium text-gray-700">Language</label>
                            <select name="language" id="language" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="en" {{ old('language', $user->language) == 'en' ? 'selected' : '' }}>English</option>
                                <option value="es" {{ old('language', $user->language) == 'es' ? 'selected' : '' }}>Spanish</option>
                                <option value="fr" {{ old('language', $user->language) == 'fr' ? 'selected' : '' }}>French</option>
                                <!-- Add other language options as needed -->
                            </select>
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('users.index') }}" class="text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
                            <x-primary-button class="ml-4">
                                {{ __('Update User') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
