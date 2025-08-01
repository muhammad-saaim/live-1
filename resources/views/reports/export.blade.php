@php
    $groupedSurveys = $UserSurveys->groupBy('survey_model_title');
    $shownPairs = [];
@endphp

<table class="table table-bordered text-center" style="border-collapse: collapse; font-size: 12px;">
    <thead>
        {{-- Row 1: Score Type --}}
        <tr>
            <th colspan="6" style="vertical-align: middle; background: #fff; border: 1px solid #000;  min-width: 40px;"></th>
            @foreach ($groupedSurveys as $surveyTitle => $questions)
                @php $uniqueQuestions = $questions->unique('question_id'); @endphp
                <th style="border: 1px solid #000; background: #eee;"></th>
                @foreach ($uniqueQuestions as $entry)
                    @php $reverse = $entry->reverse_score ?? null; @endphp
                    <th style="border: 1px solid #000;{{ $reverse === 1 ? ' color: red; font-weight: bold;' : '' }} writing-mode: vertical-rl; transform: rotate(180deg); vertical-align: bottom; padding: 2px 0; min-width: 20px;">
                        {{ $reverse === 0 ? 'Normal' : ($reverse === 1 ? 'Reverse' : 'N/A') }}
                    </th>
                @endforeach
                <th style="border: 1px solid #000; background: #ffe;"></th>
            @endforeach
        </tr>

        {{-- Row 2: Survey Titles + Question Texts --}}
        <tr>
            <th style="border: 1px solid #000;">Group ID</th>
            <th style="border: 1px solid #000;">Group Type</th>
            <th style="border: 1px solid #000;">Evaluator ID</th>
            <!-- <th style="border: 1px solid #000;">Evaluator Relation</th> -->
            <th style="border: 1px solid #000;">Evaluator Gender</th> 
            <th style="border: 1px solid #000;">Evaluatee ID</th>
            <!-- <th style="border: 1px solid #000;">Evaluatee Relation</th> -->
            <th style="border: 1px solid #000;">Evaluatee Gender</th>
            @foreach ($groupedSurveys as $surveyTitle => $questions)
                @php $uniqueQuestions = $questions->unique('question_id'); @endphp
                <th style="border: 1px solid #000; color:red; background: #eee; font-weight: bold;" >
                    {{ $surveyTitle }}
                </th>
                @foreach ($uniqueQuestions as $entry)
                    @php
                        $qText = $entry->question_text ?? 'Untitled';
                        $isRed = false;
                        if (stripos($qText, 'Self-esteem') !== false || stripos($qText, 'Reverse') !== false || stripos($qText, 'Basic Needs Satisfaction') !== false) {
                            $isRed = true;
                        }
                    @endphp
                    <th style="border: 1px solid #000; 
                        {{ $isRed ? 'color: red; font-weight: bold;' : '' }}
                        writing-mode: vertical-rl; 
                        transform: rotate(180deg); 
                        vertical-align: bottom; 
                        padding: 2px 0; 
                        min-width: 20px;">
                        {{ $qText }}
                    </th>
                @endforeach
<th style="border: 1px solid #000; background: #ffe;">
    <strong>{{ $surveyTitle }} Total</strong>
</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($UserSurveys as $entry)
            @php
                $groupType = $entry->group_name ?? ' ';
                $pairKey = $groupType . '-' . $entry->users_id . '-' . $entry->evaluatee_id;
            @endphp
            @if (!in_array($pairKey, $shownPairs))
                @php $shownPairs[] = $pairKey; @endphp
                <tr>
                    <td style="border: 1px solid #000;">{{ $entry->group_id }}</td>
                    <td style="border: 1px solid #000;">{{ $groupType }}</td>
                    <td style="border: 1px solid #000;">{{ $entry->users_id ?? 'N/A' }}</td>
                    <!-- <td style="border: 1px solid #000;">{{ $entry->evaluator_relation ?? 'N/A' }}</td> -->
                    <td style="border: 1px solid #000;">{{ $entry->user_gender ?? 'N/A' }}</td>
                    <td style="border: 1px solid #000;">{{ $entry->evaluatee_id ?? 'N/A' }}</td>
                    <!-- <td style="border: 1px solid #000;">{{ $entry->evaluatee_relation ?? 'N/A' }}</td> -->
                    <td style="border: 1px solid #000;">{{ $entry->evaluatee_gender ?? 'N/A' }}</td>
                    @foreach ($groupedSurveys as $surveyTitle => $questions)
                        @php $uniqueQuestions = $questions->unique('question_id'); @endphp
                        <td style="border: 1px solid #000; background: #f9f9f9;"></td>
                        @php $surveyRowTotal = 0; @endphp
                        @foreach ($uniqueQuestions as $questionEntry)
                            @php
                                $matched = $UserSurveys->first(function ($record) use ($entry, $questionEntry) {
                                    return $record->group_id === $entry->group_id &&
                                           $record->users_id === $entry->users_id &&
                                           $record->evaluatee_id === $entry->evaluatee_id &&
                                           $record->question_id === $questionEntry->question_id;
                                });
                                $optionName = $matched->option_name ?? '';
                                $corredcr = $matched->option_name ?? 0;
                                $surveyRowTotal += $corredcr;
                            @endphp
                            <td style="border: 1px solid #000;">{{ $optionName }}</td>
                        @endforeach
                        <td style="border: 1px solid #000; background: #ffe; font-weight: bold;">{{ $surveyRowTotal }}</td>
                    @endforeach
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
