<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Evaluator</th>
            <th>Evaluatee</th>
            <th>Survey</th>
            <th>Question</th>
            <th>Selected Option</th>
            <th>Applies To</th>
            <th>Score Type</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($UserSurveys as $index => $entry)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $entry->users_id ?? 'N/A' }}</td>
                <td>{{ $entry->evaluatee_id ?? 'N/A' }}</td>
                <td>{{ $entry->survey->title ?? 'N/A' }}</td>
                <td>{{ $entry->question->question ?? 'N/A' }}</td>
                <td>{{ $entry->option->name ?? 'N/A' }}</td>
                <td>{{ is_array($entry->survey->applies_to) ? implode(', ', $entry->survey->applies_to) : $entry->survey->applies_to }}</td>
                <td>{{ $entry->question->reverse_score === 0 ? 'Normal' : ($entry->question->reverse_score === 1 ? 'Reverse' : 'N/A') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
