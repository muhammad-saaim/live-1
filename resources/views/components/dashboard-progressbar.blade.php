@props(['num' => 0, 'max' => 0, 'completedQuestion' => 0, 'totalQuestion' => 0, 'survey_id' => null])

<div class="flex items-center justify-between w-full space-x-4">
    <!-- Survey Title -->
    <label for="file-$slot" class="w-1/6 text-left ml-2 flex-shrink-0"> {{ $slot }} </label>

    <!-- Progress Bar Container -->
    <div class="flex items-center w-5/6 space-x-4">
        <progress class="h-2 flex-grow border rounded-xl" id="file-family"
                  value="{{ $totalQuestion > 0 ? ($completedQuestion * 100) / $totalQuestion : $totalQuestion }}"
                  max="100">
        </progress>

        <!-- Completed / Total Questions -->
        <span class="ml-2 text-sm">{{ $completedQuestion }} / {{ $totalQuestion }}</span>

        <!-- Status Button with Fixed Size -->
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
                <x-primary-button class="min-w-[120px] w-24 flex justify-center items-center" type="submit">
                    Rate
                </x-primary-button>
            </form>
        @endif
    </div>
</div>
