<!-- resources/views/components/perception-table.blade.php -->
@props(['allreport', 'allGroupSurveyResults', 'surveytypequestion'])

<div class="modal fade" id="perceptionModal" tabindex="-1" aria-labelledby="perceptionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-4 border-0">
      <div class="modal-header bg-primary text-white rounded-top-4">
        <h5 class="modal-title" id="perceptionModalLabel">
          <!-- <i class="bi bi-bar-chart-fill me-2"></i>How I Am Perceived -->
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body bg-light">
        {{-- START OF TABLE --}}
        <div class="table-responsive">
       <table class="table table-bordered table-striped table-hover align-middle mb-4 shadow-sm rounded-3 perception-table">
    <thead class="table-primary">
      <tr>
<th colspan="5" style="text-align: center; font-size: 24px; font-weight: bold;">
  Personality Analysis
</th>
      </tr>
        <tr>
            <th rowspan="2" class="align-middle">Survey names</th>
            <th rowspan="2" class="align-middle">Overall %  </th>
            <th rowspan="2" class="align-middle">Self-Evaluation %</th>
            <th class="align-middle text-center">Family members %</th>
            <th class="align-middle text-center">Friends % (5 friends should evaluate)</th>
        </tr>
    </thead>
    <tbody>
        @php
            $traits = [
                'SELF' => 'Self-esteem',
                'COMPETENCE' => 'Competence',
                'AUTONOMY' => 'Autonomy',
                'RELATEDNESS' => 'Relatedness',
                'SELF-PERCEPTION' => 'Self-perception',
                'RELATIONSHIP' => 'Relationship',
                'INTROVERTS' => 'Introvert',
                'EXTRAVERT' => 'Extravert',
                'ACADEMIC' => 'Academic',
                'SOCIAL' => 'Social',
            ];

            function formatPercent($points, $ratings, $maxPoint = 5) {
                return ($ratings > 0) ? round(($points / ($ratings * $maxPoint)) * 100, 0) : null;
            }

            function quality($percent) {
                return match (true) {
                    $percent >= 84 => 'Perfect',
                    $percent >= 70 => 'Very good',
                    $percent >= 40 => 'Good',
                    default => 'Poor',
                };
            }

            // lowercase key maps for group data
            $familyMap = collect($allGroupSurveyResults['family'] ?? [])->mapWithKeys(fn($v, $k) => [strtolower($k) => $v]);
            $friendMap = collect($allGroupSurveyResults['friend'] ?? [])->mapWithKeys(fn($v, $k) => [strtolower($k) => $v]);
        @endphp

        @foreach ($traits as $key => $label)
            @php
                $lowerKey = strtolower($key);

                // Determine maxPoint based on Rosenberg
                $isRosenberg = $lowerKey === 'self'; // adjust if Rosenberg key differs
                $maxPoint = $isRosenberg ? 4 : 5;

                // Self Evaluation
                $selfPoints = $allreport['total_points'][$key] ?? 0;
                $selfRatings = $allreport['total_ratings'][$key] ?? 0;
                $selfPercent = formatPercent($selfPoints, $selfRatings, $maxPoint);
                $selfText = $selfPercent !== null ? quality($selfPercent) . ', ' . $selfPercent : ' ';

                // Family Evaluation
                $famData = $familyMap[$lowerKey] ?? null;
                $famPercent = $famData ? formatPercent($famData['total_points'], $famData['total_ratings'], $maxPoint) : null;
                $famText = $famPercent !== null ? quality($famPercent) . ', ' . $famPercent : ' ';

                // Friend Evaluation
                $friendData = $friendMap[$lowerKey] ?? null;
                $friendPercent = $friendData ? formatPercent($friendData['total_points'], $friendData['total_ratings'], $maxPoint) : null;
                $friendText = $friendPercent !== null ? quality($friendPercent) . ', ' . $friendPercent : ' ';

                // Overall Combined
                $totalPoints = $selfPoints + ($famData['total_points'] ?? 0) + ($friendData['total_points'] ?? 0);
                $totalRatings = $selfRatings + ($famData['total_ratings'] ?? 0) + ($friendData['total_ratings'] ?? 0);
                $overallPercent = formatPercent($totalPoints, $totalRatings, $maxPoint);
                $overallText = $overallPercent !== null ? quality($overallPercent) . ', ' . $overallPercent : ' ';

                // Highlight important traits
                $highlight = in_array($key, ['SELF', 'COMPETENCE', 'AUTONOMY', 'RELATEDNESS']) ? 'background-color: #fffec0;' : '';
            @endphp

            <tr style="{{ $highlight }}">
                <td>{{ $label }}</td>
                <td>{{ $overallText }}</td>
                <td>{{ $selfText }}</td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>
              
        </div>
        {{-- END OF TABLE --}}
@php
    $individual = $surveytypequestion['individual'];
    $family = $surveytypequestion['family'];
    $friend = $surveytypequestion['friend'];
    
    // Collect unique questions based on question_text
    $allQuestions = collect(array_merge(
        $individual['questions'],
        $family['questions'],
        $friend['questions']
    ))->unique('question_text')->values();
@endphp

        {{-- SECOND TABLE - UPDATED FOR SELF AND OVERALL ONLY --}}
        <div class="table-responsive">
         {{-- SECOND TABLE - STYLED TO MATCH THE FIRST --}}
<div class="table-responsive">
  <table class="table table-bordered table-striped table-hover align-middle mb-4 shadow-sm rounded-3 perception-table">
    <thead class="table-primary">
            <tr>
<th colspan="5" style="text-align: center; font-size: 24px; font-weight: bold;">
  How I am Perceived
</th>
      <tr>
            <th rowspan="2">Questions</th>
        </tr>
        <tr>
            <th>Overall %</th>
            <th>Self-Evaluation %</th>
            <th>Family members %</th>
            <th>Friends %</th>
        </tr>
    </thead>
    <tbody>
        @foreach($allQuestions as $question)
            @php
                $text = $question['question_text'];
                $selfQuestion = collect($individual['questions'])->firstWhere('question_text', $text);
                $selfPercentage = null;
                if ($selfQuestion && $selfQuestion['self_total_points'] > 0) {
                    $selfPercentage = round(($selfQuestion['self_total_points'] / ($selfQuestion['self_total_ratings']*5)) * 100);
                }

                $famQuestion = collect($family['questions'])->firstWhere('question_text', $text);
                $famPercentage = null;
                $totalFamilyPoints = 0;
                $totalFamilyRatings = 0;
                if ($famQuestion) {
                    $totalFamilyPoints = ($famQuestion['others_total_points'] ?? 0) + ($famQuestion['self_total_points'] ?? 0);
                    $totalFamilyRatings = ($famQuestion['others_total_ratings'] ?? 0) + ($famQuestion['self_total_ratings'] ?? 0);
                    if ($totalFamilyRatings > 0) {
                        $famPercentage = round(($totalFamilyPoints /($totalFamilyRatings * 5)) * 100);
                    }
                }

                $frndQuestion = collect($friend['questions'])->firstWhere('question_text', $text);
                $frndPercentage = null;
                $totalFriendPoints = 0;
                $totalFriendRatings = 0;
                if ($frndQuestion) {
                    $totalFriendPoints = ($frndQuestion['others_total_points'] ?? 0) + ($frndQuestion['self_total_points'] ?? 0);
                    $totalFriendRatings = ($frndQuestion['others_total_ratings'] ?? 0) + ($frndQuestion['self_total_ratings'] ?? 0);
                    if ($totalFriendRatings > 0) {
                        $frndPercentage = round(($totalFriendPoints / ($totalFriendRatings * 5)) * 100);
                    }
                }

                $getStatus = function($percentage) {
                    if ($percentage === null) return '-';
                    return match (true) {
                        $percentage >= 84 => 'Perfect',
                        $percentage >= 60 => 'Very Good',
                        $percentage >= 40 => 'Good',
                        $percentage > 0 => 'Poor',
                        default => '-',
                    };
                };

                $format = fn($percentage) => $percentage === null ? '-' : $getStatus($percentage) . ', ' . $percentage . '  ';

                $selfPoints = $selfQuestion['self_total_points'] ?? 0;
                $selfRatings = $selfQuestion['self_total_ratings'] ?? 0;
                $totalPoints = $selfPoints + $totalFamilyPoints + $totalFriendPoints;
                $totalRatings = $selfRatings + $totalFamilyRatings + $totalFriendRatings;

                $avgPercentage = ($totalRatings > 0) ? round(($totalPoints / ($totalRatings * 5)) * 100) : null;
            @endphp

            <tr>
                <td>{{ $text ?: 'Question ' . $question['question_id'] }}</td>
                <td>{{ $avgPercentage ? $getStatus($avgPercentage) . ', ' . $avgPercentage . '  ' : '-' }}</td>
                <td>{{ $format($selfPercentage) }}</td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>

        </div>
      </div>
    </div>
  </div>
</div>

<style>
/* Modal custom styles */
.modal-content {
  border-radius: 1.2rem;
  border: none;
}
.modal-header {
  border-bottom: 2px solid #e3e3e3;
  padding-top: 1rem;
  padding-bottom: 1rem;
}
.modal-body {
  padding: 2rem 1.5rem;
  background: #f8fafc;
  border-radius: 0 0 1.2rem 1.2rem;
}

/* Table custom styles */
.perception-table {
  font-size: 13px;
  border-radius: 0.7rem;
  overflow: hidden;
  background: #fff;
}
.perception-table th, .perception-table td {
  vertical-align: middle;
  border: 1px solid #dee2e6;
}
.perception-table th {
  background: #e3f0fc;
  color: #1a237e;
  font-weight: 600;
}
.perception-table tr:nth-child(even) {
  background: #f6fafd;
}
.perception-table tr:hover {
  background: #e3f0fc;
  transition: background 0.2s;
}
.perception-table td:first-child {
  font-weight: 500;
  color: #333;
  background: #f3f3f3;
}
</style>
