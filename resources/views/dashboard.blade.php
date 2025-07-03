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
                            completedQuestion="{{ auth()->user()->usersSurveysRates->where('survey_id', $survey->id)->where('users_id', auth()->id())->where('evaluatee_id', auth()->id())->count() }}"
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
                    <div style="background-color: {{ $group->color }};"
                        class="rounded-xl border border-gray-300 px-3 pt-2 pb-3">
                        <h1 class="font-semibold text-lg mb-2">{{ $group->name ?? __('Group Name') }}</h1>
                        <p class="text-gray-700 ml-2">{{ __('User') }}: {{ $group->users()->count() }}</p>
                        <p class="text-gray-500 ml-2 text-sm">{{ __('Created') }}
                            : {{ $group->created_at->format('d/m/Y') }}
                        </p>
                        <div class="">
                            @php
                                $allSurveys = $group->defaultSurveys();
                                $totalQuestions = 0;
                                $completedQuestions = 0;

                                foreach ($allSurveys as $survey) {
                                    $totalQuestions += $survey->questions->count();
                                    $completedQuestions += $survey->usersSurveysRates()
                                        ->where('users_id', auth()->id())
                                        ->where('evaluatee_id', auth()->id())
                                        ->count();
                                }

                                $selfPercentage = $totalQuestions > 0 ? round(($completedQuestions / $totalQuestions) * 100, 1) : 0;

                                // For others' completion rate
                                $othersCompletedQuestions = 0;
                                foreach ($allSurveys as $survey) {
                                    $othersCompletedQuestions += $survey->usersSurveysRates()
                                        ->where('users_id', auth()->id())
                                        ->where('evaluatee_id', '!=', auth()->id())
                                        ->count();
                                }

                                $othersPercentage = $totalQuestions > 0 ? round(($othersCompletedQuestions / $totalQuestions) * 100, 1) : 0;
                            @endphp
                            <x-group-progressbar class="mb-2" :num="$selfPercentage">Me </x-group-progressbar>
                            <x-group-progressbar :num="$othersPercentage">Others </x-group-progressbar>
                        </div>

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
