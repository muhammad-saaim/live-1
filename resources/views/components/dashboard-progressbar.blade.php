@props(['num' => 0, 'max' => 0, 'completedQuestion' => 0, 'totalQuestion' => 0, 'survey_id' => null, 'group'=> null,
'total_points' => 0, 'points_self' => 0, 'points_competence' => 0, 'points_autonomy' => 0, 'points_relatedness' => 0,])

@php
$modalId = 'report-modal-' . $survey_id;
@endphp

<div x-data="{ showModal: false }" class="flex items-center justify-between w-full">
    <!-- Survey Title & Report Button -->
    <div class="w-1/6 text-left ml-2 flex-shrink-0">
        <label for="file-{{ $slot }}" class="block">{{ $slot }}
            @if($total_points > 0)
            <button type="button"
                style="padding:2px 5px"
                class="inline-flex items-center border border-blue-500 text-blue-600 rounded text-xs transition hover:bg-blue-50 hover:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-200 shadow-sm"
                @click="showModal = true">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 text-blue-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2a4 4 0 014-4h3m4 4v6a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6" />
                </svg>
                Report
            </button>
            @endif
        </label>
    </div>

    <!-- Progress Bar & Actions -->
    <div class="flex items-center w-5/6 space-x-4">
        <progress class="h-2 flex-grow border rounded-xl" id="file-family"
            value="{{ $totalQuestion > 0 ? ($completedQuestion * 100) / $totalQuestion : $totalQuestion }}" max="100">
        </progress>

        <span class="ml-2 text-sm">{{ $completedQuestion }} / {{ $totalQuestion }}</span>

        @if($completedQuestion == $totalQuestion)
        <form action="{{ route('survey.ShowSurvey',$survey_id) }}" method="POST">
            @csrf
            <input type="hidden" name="survey_id" value="{{ $survey_id }}">
            <x-outline-button class="min-w-[120px] w-24 flex justify-center items-center">
                Completed
            </x-outline-button>
        </form>
        @else
        <form action="{{ route('rate.survey') }}" method="POST">
            @csrf
            <input type="hidden" name="survey_id" value="{{ $survey_id }}">
            @if ($group)
            <input type="hidden" name="group_id" value="{{ $group->id }}">
            @endif
            <x-primary-button class="min-w-[120px] w-24 flex justify-center items-center" type="submit">
                Rate
            </x-primary-button>
        </form>
        @endif
    </div>

    @include('components.progress-report-modal', [
        'total_points' => $total_points,
        'points_self' => $points_self,
        'points_competence' => $points_competence,
        'points_autonomy' => $points_autonomy,
        'points_relatedness' => $points_relatedness,
    ])
</div>