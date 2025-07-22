<!-- Group Survey Data Modal -->
<div x-show="{{ $alpineState }}" x-cloak
    class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
    <div @click.away="{{ $alpineState }} = false"
        class="bg-white rounded-2xl p-4 max-w-xl w-full mx-4 shadow-2xl relative animate-fade-in overflow-y-auto max-h-[90vh]">

        <!-- Close Button -->
        <button @click="{{ $alpineState }} = false"
            class="absolute top-4 right-4 text-gray-400 hover:text-blue-600 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Modal Header -->
        <div class="flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-2a4 4 0 014-4h3m4 4v6a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6" />
            </svg>
            <h2 class="text-xl font-bold text-gray-800">Survey Evaluation Summary</h2>
        </div>

        <div class="border rounded-lg p-3 shadow-sm space-y-6">
            <!-- Self Evaluation -->
            @php
                $totalSelfRatings = $groupSurveyTypePoints['totals']['self']['total_ratings'];
            @endphp
            @if ($totalSelfRatings >= 2)
                @php
                    $totalPoints = $groupSurveyTypePoints['totals']['self']['total_points'];
                @endphp
                @if ($totalPoints > 0)
                <div>
                    <h4 class="font-medium text-gray-600 mb-2">Self Evaluation</h4>
                    <ul class="text-sm text-gray-700 space-y-1">
                        @foreach ($groupSurveyTypePoints as $type => $data)
                            @php
                                $points = $data['self']['total_points'];
                                $ratings = $data['self']['total_ratings'];
                                $maxPoints = $ratings * 5;
                                $percentage = $maxPoints > 0 ? round(($points / $maxPoints) * 100, 1) : 0;
                                if ($percentage >= 84) {
                                    $status = 'Perfect';
                                    $statusColor = 'text-green-600';
                                } elseif ($percentage >= 70) {
                                    $status = 'Very Good';
                                    $statusColor = 'text-blue-600';
                                } elseif ($percentage >= 40) {
                                    $status = 'Good';
                                    $statusColor = 'text-yellow-600';
                                } else {
                                    $status = 'Poor';
                                    $statusColor = 'text-red-500';
                                }
                            @endphp
                            @if ($points > 0)
                            <li class="flex items-center space-x-2">
                                <div class="flex-1 flex items-center justify-between">
                                    <span>{{ $type }}: {{ $points }} points, {{ $ratings }} ratings ({{ $percentage }}%)</span>
                                    <div class="flex-grow mx-2 border-t border-dashed border-gray-300"></div>
                                    <span class="font-bold {{ $statusColor }}">{{ $status }}</span>
                                </div>
                            </li>
                            @endif
                        @endforeach

                        @php
                            $totalRatings = $totalSelfRatings;
                            $totalMaxPoints = $totalRatings * 5;
                            $totalPercentage = $totalMaxPoints > 0 ? round(($totalPoints / $totalMaxPoints) * 100, 1) : 0;
                            if ($totalPercentage >= 84) {
                                $totalStatus = 'Perfect';
                                $totalStatusColor = 'text-green-600';
                            } elseif ($totalPercentage >= 70) {
                                $totalStatus = 'Very Good';
                                $totalStatusColor = 'text-blue-600';
                            } elseif ($totalPercentage >= 40) {
                                $totalStatus = 'Good';
                                $totalStatusColor = 'text-yellow-600';
                            } else {
                                $totalStatus = 'Poor';
                                $totalStatusColor = 'text-red-500';
                            }
                        @endphp
                        <li class="flex items-center space-x-2 mt-1 font-bold">
                            <div class="flex-1 flex items-center justify-between">
                                <span>Total Self: {{ $totalPoints }} points, {{ $totalRatings }} ratings ({{ $totalPercentage }}%)</span>
                                <div class="flex-grow mx-2 border-t border-dashed border-gray-300"></div>
                                <span class="{{ $totalStatusColor }}">{{ $totalStatus }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
                @endif
            @endif

            <!-- Others Evaluation -->
            @php
                $totalOthersRatings = $groupSurveyTypePoints['totals']['others']['total_ratings'];
                $totalOthersPoints = $groupSurveyTypePoints['totals']['others']['total_points'];
            @endphp
            @if ($totalOthersRatings >= 2)
                @if ($totalOthersPoints > 0)
                <div>
                    <h4 class="font-medium text-gray-600 mb-2">Others Evaluation</h4>
                    <ul class="text-sm text-gray-700 space-y-1">
                        @foreach ($groupSurveyTypePoints as $type => $data)
                            @php
                                $points = $data['others']['total_points'];
                                $ratings = $data['others']['total_ratings'];
                                $maxPoints = $ratings * 5;
                                $percentage = $maxPoints > 0 ? round(($points / $maxPoints) * 100, 1) : 0;
                                if ($percentage >= 84) {
                                    $status = 'Perfect';
                                    $statusColor = 'text-green-600';
                                } elseif ($percentage >= 70) {
                                    $status = 'Very Good';
                                    $statusColor = 'text-blue-600';
                                } elseif ($percentage >= 40) {
                                    $status = 'Good';
                                    $statusColor = 'text-yellow-600';
                                } else {
                                    $status = 'Poor';
                                    $statusColor = 'text-red-500';
                                }
                            @endphp
                            @if ($points > 0)
                            <li class="flex items-center space-x-2">
                                <div class="flex-1 flex items-center justify-between">
                                    <span>{{ $type }}: {{ $points }} points, {{ $ratings }} ratings ({{ $percentage }}%)</span>
                                    <div class="flex-grow mx-2 border-t border-dashed border-gray-300"></div>
                                    <span class="font-bold {{ $statusColor }}">{{ $status }}</span>
                                </div>
                            </li>
                            @endif
                        @endforeach

                        @php
                            $totalRatings = $totalOthersRatings;
                            $totalMaxPoints = $totalRatings * 5;
                            $totalPercentage = $totalMaxPoints > 0 ? round(($totalOthersPoints / $totalMaxPoints) * 100, 1) : 0;
                            if ($totalPercentage >= 84) {
                                $totalStatus = 'Perfect';
                                $totalStatusColor = 'text-green-600';
                            } elseif ($totalPercentage >= 70) {
                                $totalStatus = 'Very Good';
                                $totalStatusColor = 'text-blue-600';
                            } elseif ($totalPercentage >= 40) {
                                $totalStatus = 'Good';
                                $totalStatusColor = 'text-yellow-600';
                            } else {
                                $totalStatus = 'Poor';
                                $totalStatusColor = 'text-red-500';
                            }
                        @endphp
                        <li class="flex items-center space-x-2 mt-1 font-bold">
                            <div class="flex-1 flex items-center justify-between">
                                <span>Total Others: {{ $totalOthersPoints }} points, {{ $totalRatings }} ratings ({{ $totalPercentage }}%)</span>
                                <div class="flex-grow mx-2 border-t border-dashed border-gray-300"></div>
                                <span class="{{ $totalStatusColor }}">{{ $totalStatus }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
                @endif
            @endif
        </div>
    </div>
</div>
