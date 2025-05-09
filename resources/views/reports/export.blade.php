<table>
    <thead>
        <tr>
            <th>#</th>
            <th>User</th>
            <th>Survey</th>
            <th>Question</th>
            <th>Selected Option</th>
            <th>Applies To</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($UserSurveys as $index => $entry)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $entry->user->name ?? 'N/A' }}</td>
                <td>{{ $entry->survey->title ?? 'N/A' }}</td>
                <td>{{ $entry->question->question ?? 'N/A' }}</td>
                <td>{{ $entry->option->name ?? 'N/A' }}</td>
                <td>{{ is_array($entry->survey->applies_to) ? implode(', ', $entry->survey->applies_to) : $entry->survey->applies_to }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
