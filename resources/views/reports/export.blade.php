@php
    // Get unique type names
    $typeNames = $UserSurveys->pluck('types.name')->filter()->unique();

    // Calculate totals per type
    $totalsByType = [];

    foreach ($typeNames as $typeName) {
        $totalsByType[$typeName] = $UserSurveys->filter(function ($entry) use ($typeName) {
            return $entry->types->name === $typeName;
        })->sum(function ($entry) {
            return is_numeric($entry->option->name) ? (int) $entry->option->name : 0;
        });
    }
@endphp

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>#</th>
            <th>Group Id</th>
            <th>Group Name</th>
            <th>Evaluator</th>
            <th>Evaluatee</th>
            <th>Survey</th>
            <th>Survey Type</th>
            <th>Question</th>
            <th>Question Type</th>
            <th>Selected Option</th>
            <th>Applies To</th>
            <th>Score Type</th>
            @foreach ($typeNames as $typeName)
                <th>{{ $typeName }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($UserSurveys as $index => $entry)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $entry->group_id ?? 'N/A' }}</td>
                <td>{{ $entry->group->name ?? 'N/A' }}</td>
                <td>{{ $entry->users_id ?? 'N/A' }}</td>
                <td>{{ $entry->evaluatee_id ?? 'N/A' }}</td>
                <td>{{ $entry->survey->title ?? 'N/A' }}</td>
                <td>{{ $entry->surveyModel->title ?? 'N/A' }}</td>
                <td>{{ $entry->question->question ?? 'N/A' }}</td>
                <td>{{ $entry->types->name ?? 'N/A' }}</td>
                <td>{{ $entry->option->name ?? 'N/A' }}</td>
                <td>
                    {{ is_array($entry->survey->applies_to)
                        ? implode(', ', $entry->survey->applies_to)
                        : ($entry->survey->applies_to ?? 'N/A') }}
                </td>
                <td>
                    {{ $entry->question->reverse_score === 0
                        ? 'Normal'
                        : ($entry->question->reverse_score === 1 ? 'Reverse' : 'N/A') }}
                </td>

                @foreach ($typeNames as $typeName)
                    <td>
                        {{ $entry->types->name === $typeName
                            ? ($entry->option->name ?? 0)
                            : 0 }}
                    </td>
                @endforeach
            </tr>
        @endforeach

        {{-- Totals Row --}}
        <tr>
            <td colspan="12"><strong>Total Points by Type</strong></td>
            @foreach ($typeNames as $typeName)
                <td><strong>{{ $totalsByType[$typeName] }}</strong></td>
            @endforeach
        </tr>
    </tbody>
</table>
