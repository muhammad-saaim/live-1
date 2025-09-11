<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Mentor</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Clients who shared with you</h3>
                @if($clients->isEmpty())
                    <p class="text-gray-600">No clients have shared with you yet.</n></p>
                @else
                    <ul class="divide-y divide-gray-200">
                        @foreach($clients as $client)
                            <li class="py-3 flex items-center justify-between">
                                <div>
                                    <div class="font-medium">{{ $client->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $client->email }}</div>
                                </div>
                                <a class="text-indigo-600 hover:underline" href="{{ route('mentor.client', $client->id) }}">View reports</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>


