<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $client->name }} - Reports</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if($UserSurveys->isEmpty())
                    <p class="text-gray-600">No survey data available.</p>
                @else
                    <ul class="divide-y divide-gray-200">
                        @foreach ($UserSurveys as $survey)
                            @php
                                $sid = $survey?->id;
                                $avg = $sid ? ($surveyAverages[$sid] ?? 0) : 0;
                            @endphp
                            <li class="py-3 flex items-center justify-between">
                                <div>
                                    <div class="font-medium">{{ $survey?->title ?? 'Untitled' }}</div>
                                    <div class="text-sm text-gray-500">Average: {{ number_format($avg, 2) }}</div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>


