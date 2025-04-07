<x-app-layout>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Error Message -->
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">{{ $group->name }}</h2>
                        <p class="my-2 text-gray-600">{{ $group->description }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="my-2 text-gray-600">User(s): {{ $group->users()->count() }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="my-2 text-gray-600">Applies to:
                            @foreach ($group->groupTypes as $groupType)
                                {{ $groupType->name }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </p>
                    </div>

                    <div class="my-3 text-sm text-gray-500">
                        <span class="me-2">Updated on: {{ $group->updated_at->format('M d, Y') }}</span>|<span class="ms-2">Created on: {{ $group->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="my-3 flex space-x-2">
                        <a href="{{ route('group.edit', $group->id) }}">
                            <x-secondary-button>Edit</x-secondary-button>
                        </a>
                        <form action="{{ route('group.destroy', $group->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this group?');">
                            @csrf
                            @method('DELETE')
                            <x-primary-button type="submit">Delete</x-primary-button>
                        </form>
                        <form action="{{ route('group.invite') }}" method="POST">
                            @csrf
                            <input type="hidden" name="group_id" value="{{ $group->id }}">
                            <div class="flex space-x-2 align-center">
                                <x-form-input-small name="email" type="email" placeholder="Enter email" />
                                <x-outline-button type="submit">Invite</x-outline-button>
                            </div>
                        </form>

                    </div>
                    <hr class="my-3">
                    <div class="my-3">
                        <h3 class="text-xl font-semibold text-gray-800">Users in this Group</h3>
                        <table class="min-w-full mt-4 bg-white border border-gray-200 rounded-lg">
                            <thead>
                            <tr class="bg-gray-100 border-b">
                                <th class="px-4 py-2 text-left text-gray-600">#</th>
                                <th class="px-4 py-2 text-left text-gray-600">Name</th>
                                <th class="px-4 py-2 text-left text-gray-600">Email</th>
                                <th class="px-4 py-2 text-left text-gray-600">Joined At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($group->users as $user)
                                <tr class="border-b">
                                    <td class="px-4 py-2 text-gray-700">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-2 text-gray-700">{{ $user->name }}</td>
                                    <td class="px-4 py-2 text-gray-700">{{ $user->email }}</td>
                                    <td class="px-4 py-2 text-gray-700">
                                        {{ $user->pivot->created_at ? $user->pivot->created_at->format('M d, Y') : 'N/A' }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
