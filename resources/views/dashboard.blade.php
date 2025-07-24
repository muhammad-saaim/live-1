<x-app-layout>
    {{--    <x-slot name="header">--}}
    {{--        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">--}}
    {{--            {{ __('Dashboard') }}--}}
    {{--        </h2>--}}
    {{--    </x-slot>--}}

    <div class="p-3 max-w-7xl mx-auto space-y-4">
        <!-- Buttons -->
        <div class="flex justify-between align-center">
            <div>
                <h2 class="font-bold text-2xl"> {{__("WELCOME")}} </h2>
                <p class="mb-3 text-l">Your report is preferring.</p>
            </div>
            <div>
                <x-primary-button>
                    <svg class="h-3 w-3 text-white me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="4"
                         stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <a href="{{ route('reports.index') }}">Reports</a>
                </x-primary-button>
                <a href="{{ route('group.create') }}">
                    <x-secondary-button>
                        <svg class="h-3 w-3 text-gray-800 me-1" width="24" height="24" viewBox="0 0 24 24"
                             stroke-width="4" stroke="currentColor" fill="none" stroke-linecap="round"
                             stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z"/>
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        New Group
                    </x-secondary-button>
                </a>

            </div>
        </div>

        {{-- Success and Error Messages --}}
        @if(session('success'))
            <div class="bg-green-500 text-white p-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500 text-white p-3 rounded">
                {{ session('error') }}
            </div>
        @endif
        <!-- User Surveys -->
        <div>
            <div class="bg-ml-color-lime border rounded-xl p-4 space-y-3">
                <h2 class="text-xl mb-0"> {{ __("My Surveys") }} </h2>
                <p class="text-xs my-0">Date: {{ now() }} </p>
                @php
                    // $userSurveys = auth()->user()->surveys; 

                    $individualSurveys = \App\Models\Survey::where('is_active', true)
                        ->whereJsonContains('applies_to', 'Individual')
                        ->get();

                    // $allSurveys = $userSurveys->merge($individualSurveys)->unique('id');
                @endphp

                @if($individualSurveys->isNotEmpty())
                    @foreach($individualSurveys as $survey)
                        <x-dashboard-progressbar
                            completedQuestion="{{ auth()->user()->usersSurveysRates->where('survey_id', $survey->id)->where('users_id', auth()->id())->where('evaluatee_id', auth()->id())->whereNull('group_id')->count()  }}"
                            survey_id="{{ $survey->id }}"
                            totalQuestion="{{ $survey->questions->count() }}"
                            total_points="{{ $surveyPoints[$survey->id] ?? 0 }}"
                            points_self="{{ $typePoints['SELF'][$survey->id] ?? 0 }}"
                            points_competence="{{ $typePoints['COMPETENCE'][$survey->id] ?? 0 }}"
                            points_autonomy="{{ $typePoints['AUTONOMY'][$survey->id] ?? 0 }}"
                            points_relatedness="{{ $typePoints['RELATEDNESS'][$survey->id] ?? 0 }}"
                        >
                            {{ $survey->title }}
                        </x-dashboard-progressbar>
                    @endforeach
                @else
                    <p>{{ __("No surveys assigned.") }}</p>
                @endif

            </div>
        </div>

        <!-- Groups -->
        @if(auth()->user()->groups()->exists())
            <div class="pb-10">
                <div>
                    <div class="flex justify-start items-center mt-5">
                        <h2 class="text-3xl font-bold me-2"> {{__("Groups")}} </h2>
                        <a href="{{ route('group.create') }}">
                            <x-secondary-button>New</x-secondary-button>
                        </a>
                    </div>
                    <div>
                        <p class="mb-3 text-l">Groups you've created for your family and close friends are listed
                            here.</p>
                    </div>

                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach(auth()->user()->groups as $group)
                          @php
        $groupSurveyTypetotalPoints = calculateSurveyTypetotalPoints($group);
    $combined = $groupSurveyTypetotalPoints['combined_totals_by_type'] ?? [];
@endphp



        @php 
            $groupSurveyTypePoints = calculateSurveyTypePoints($group);

            $selfTotals = $groupSurveyTypePoints['all_surveys_totals']['self'] ?? null;
            $othersTotals = $groupSurveyTypePoints['all_surveys_totals']['others'] ?? null;

            $selfPoints = $selfTotals['total_points'] ?? 0;
            $selfRatings = $selfTotals['total_ratings'] ?? 0;
            $selfMaxPoints = $selfRatings * 5;
            $selfPercentage = $selfMaxPoints > 0 ? round(($selfPoints / $selfMaxPoints) * 100, 1) : 0;

            $othersPoints = $othersTotals['total_points'] ?? 0;
            $othersRatings = $othersTotals['total_ratings'] ?? 0;
            $othersMaxPoints = $othersRatings * 5;
            $othersPercentage = $othersMaxPoints > 0 ? round(($othersPoints / $othersMaxPoints) * 100, 1) : 0;

            
        @endphp
                       <div style="background-color: {{ $group->color }};" class="rounded-xl border border-gray-300 px-3 pt-2 pb-3">
            <div x-data="{ showCombinedModal_{{ $group->id }}: false }" class="mt-3">
                            <h1 class="font-semibold text-lg mb-2">{{ $group->name ?? __('Group Name') }}

                <!-- Trigger Button -->
              <button
    @click="showCombinedModal_{{ $group->id }} = true"
    class="inline-flex items-center bg-blue-500 text-white border border-blue-600 rounded text-[10px] px-2 py-1 hover:bg-blue-600 transition">
    Group Report
</button>


</h1>

    <!-- Modal -->
   
   
   
    <div
        x-show="showCombinedModal_{{ $group->id }}"
        x-cloak
        @click="showCombinedModal_{{ $group->id }} = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
        <div
            class="bg-white rounded-lg shadow-lg p-6 w-full max-w-xl"
            @click.stop
        >
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Combined Totals by Type</h2>
                
            </div>
@php
                if (!function_exists('getStatusModal')) {
                    function getStatusModal($percentage) {
                        if ($percentage >= 84) return ['Perfect', 'text-green-600'];
                        elseif ($percentage >= 70) return ['Very Good', 'text-blue-600'];
                        elseif ($percentage >= 40) return ['Good', 'text-yellow-600'];
                        else return ['Poor', 'text-red-500'];
                    }
                }
            @endphp
            @if(!empty($combined))
                <table class="w-full text-sm text-left border">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="p-2">Type</th>
                            <th class="p-2 text-center">Self Points</th>
                           <th class="p-2 text-center">Self Rating</th>
                            <th class="p-2 text-center">Others Points</th>
                             <th class="p-2 text-center">Others Rating</th>
                             <th class="p-2 text-center">Self (%)</th>
            <th class="p-2 text-center">Others (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($combined as $type => $data)
                         @php
                $selfPoints = $data['self']['total_points'] ?? 0;
                $selfRatings = $data['self']['total_ratings'] ?? 0;
                $selfMax = $selfRatings * 5;
                // dd($selfMax);
                $self_Percent = $selfMax > 0 ? number_format(($selfPoints / $selfMax) * 100, 1) : 0;
                 
                $othersPoints = $data['others']['total_points'] ?? 0;
                $othersRatings = $data['others']['total_ratings'] ?? 0;
                $othersMax = $othersRatings * 5;
                 $others_Percent = $othersMax > 0 ? number_format(($othersPoints / $othersMax) * 100, 1) : 0;
           [$self_Status, $self_Color] = getStatusModal($self_Percent);
                                [$others_Status, $others_Color] = getStatusModal($others_Percent);
                            @endphp
                            <tr class="border-b">
                                <td class="p-2 font-medium">{{ $type }}</td>
                                <td class="p-2 text-center">{{ $selfPoints }}</td>
                                <td class="p-2 text-center">{{ $selfRatings }}</td>
                                <td class="p-2 text-center">{{ $othersPoints }}</td>
                                <td class="p-2 text-center">{{ $othersRatings }}</td>
                                <td class="p-2 text-center">
                                    <span class="{{ $self_Color }}">{{ $self_Percent }}% ({{ $self_Status }})</span>
                                </td>
                                <td class="p-2 text-center">
                                    <span class="{{ $others_Color }}">{{ $others_Percent }}% ({{ $others_Status }})</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No combined totals available for this group.</p>
            @endif

            <div class="mt-4 text-right">
                <button
                    @click="showCombinedModal_{{ $group->id }} = false"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
                        <p class="text-gray-700 ml-2">{{ __('User') }}: {{ $group->users()->count() }}</p>
                        <p class="text-gray-500 ml-2 text-sm">{{ __('Created') }}
                            : {{ $group->created_at->format('d/m/Y') }}
                        </p>
                        <div class="">
                            @php
                                $allSurveys = $group->defaultSurveys()->filter(function ($survey) use ($group) {
        return in_array('Family', $survey->applies_to); // Ensure surveys are scoped to 'Family'
    });

    $totalQuestions = 0;
    $completedQuestions = 0;

    foreach ($allSurveys as $survey) {
        $totalQuestions += $survey->questions->count();
        $completedQuestions += $survey->usersSurveysRates()
            ->where('users_id', auth()->id())
            ->where('evaluatee_id', auth()->id())
            ->where('group_id', $group->id) // Filter by group ID
            ->count();
    }

    $selfPercentage = $totalQuestions > 0 ? round(($completedQuestions / $totalQuestions) * 100, 1) : 0;

    // For others' completion rate
    $othersCompletedQuestions = 0;
    foreach ($allSurveys as $survey) {
        $othersCompletedQuestions += $survey->usersSurveysRates()
            ->where('users_id', auth()->id())
            ->where('evaluatee_id', '!=', auth()->id())
            ->where('group_id', $group->id) // Filter by group ID
            ->count();
    }

    $othersPercentage = $totalQuestions > 0 ? round(($othersCompletedQuestions / $totalQuestions) * 100, 1) : 0;

    if (!function_exists('getStatus')) {
        function getStatus($percentage) {
            if ($percentage >= 84) return ['Perfect', 'text-green-600'];
            elseif ($percentage >= 70) return ['Very Good', 'text-blue-600'];
            elseif ($percentage >= 40) return ['Good', 'text-yellow-600'];
            else return ['Poor', 'text-red-500'];
        }
    }

    [$selfStatus, $selfColor] = getStatus($selfPercentage);
    [$othersStatus, $othersColor] = getStatus($othersPercentage);
                            @endphp
                            <x-group-progressbar class="mb-2" :num="$selfPercentage" :selfStatus="$selfStatus"
                                    :selfColor="$selfColor">Me </x-group-progressbar>
                                <x-group-progressbar :num="$othersPercentage" :othersStatus="$othersStatus"
                                    :othersColor="$othersColor">Others </x-group-progressbar>
                                   </div>
@php
                    $minimumUsers = $group->groupTypes->contains('name', 'Family') ? 2 : 6;
                    @endphp
                                        @if ($group->users()->count() >= $minimumUsers)

                        <div class="space-y-3 mt-3 p-2">
                            @foreach($group->defaultSurveys() as $survey)
                                <div class="flex items-center justify-between space-x-2">
                                    <label for="survey-{{ $survey->id }}" class="w-2/5 text-gray-600 truncate whitespace-nowrap overflow-hidden" title="{{ $survey->title }}">{{ $survey->title }}</label>
                                    <form action="{{ route('rate.survey') }}" method="POST" class="w-1/4">
                                        @csrf
                                        <input type="hidden" name="survey_id" value="{{ $survey->id }}">
                                        <input type="hidden" name="group_id" value="{{ $group->id }}">
                                        <button type="submit" class="w-full bg-white text-ml-color-lime border border-ml-color-lime rounded-xl px-2 py-1 hover:bg-ml-color-sky transition text-center text-secondary">
                                            Rate
                                        </button>
                                    </form>
                                    <select id="survey-{{ $survey->id }}"
                                        class="w-1/2 border border-gray-300 rounded-xl px-2 py-1 text-gray-600">
                                        <option value="status">
                                            {{ $survey->users()->wherePivot('is_completed', true)->count() }} / {{ $group->users->count() }} {{ __('Completed') }}
                                        </option>
                                    </select>
                                </div>
                            @endforeach
                        </div>
                          @endif
                       
                            <div class="pt-3">
                                <a href="{{ route('group.show',$group->id) }}">
                                    <x-secondary-button class=" flex justify-center w-full">
                                        Details
                                    </x-secondary-button>
                                </a>
                            </div>
                            </form>
                        
                    </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
