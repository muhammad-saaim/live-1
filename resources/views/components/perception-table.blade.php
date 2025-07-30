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
                <th rowspan="2" class="align-middle">Survey names</th>
                <th colspan="4" class="align-middle">Personality Analysis</th>
              </tr>
              <tr>
                <th>Overall (average)</th>
                <th>Self-Evaluation</th>
                {{-- <th>Family members</th>
                <th>Friends</th> --}}
              </tr>
            </thead>
            <tbody>
              {{-- Individual Self Metrics --}}
              @php
                $selfMetrics = [
                  'SELF' => 'Self-esteem',
                  'COMPETENCE' => 'Competence',
                  'AUTONOMY' => 'Autonomy',
                  'RELATEDNESS' => 'Relatedness',
                ];
              @endphp

                     @foreach ($selfMetrics as $key => $label)
    @php
        $points = $allreport['points'][$key] ?? 0;
        $ratings = $allreport['ratings'][$key] ?? 0;
            //  dd($points, $ratings);
        // Calculate percentage only if ratings > 0
        $percentage = ($ratings > 0) ? round(($points / ($ratings * 4)) * 100, 2) : 0;

        // Assign quality based on percentage
        $quality = match (true) {
            $percentage >= 84 => ' Perfect',
            $percentage >= 70 => 'Very Good',
            $percentage >= 40 => 'Good',
            default => 'Poor',
        };
    @endphp
    <tr style="background-color: #fffec0;">
        <td>{{ $label }}</td>
        <td>{{ "$percentage% ($quality)" }}</td>
        <td>{{ "$percentage% ($quality)" }}</td>
        <td>-</td>
        <td>-</td>
    </tr>
@endforeach
            </tbody>
          </table>
        </div>
        {{-- END OF TABLE --}}

        {{-- SECOND TABLE - UPDATED FOR SELF AND OVERALL ONLY --}}
        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle shadow-sm rounded-3 perception-table">
            <thead class="table-primary">
              <tr>
                <th rowspan="2">Questions</th>
                <th colspan="2">How I am Perceived</th>
              </tr>
              <tr>
                <th>Overall</th>
                <th>Self-Evaluation</th>
              </tr>
            </thead>
            <tbody>
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

                $getStatus = function($percentage) {
                    if ($percentage === null) return '-';
                    if ($percentage >= 84) return 'Perfect';
                    elseif ($percentage >= 60) return 'Very Good';
                    elseif ($percentage >= 40) return 'Good';
                    elseif ($percentage > 0) return 'Poor';
                    return '-';
                };

                $format = function($percentage) use ($getStatus) {
                    if ($percentage === null) return '-';
                    return $getStatus($percentage) . ', ' . $percentage . '%';
                };
              @endphp

              @foreach($allQuestions as $question)
                @php
                  $text = $question['question_text'];

                  // Get individual (self) data
                  $selfQuestion = collect($individual['questions'])->firstWhere('question_text', $text);
                  $selfPercentage = null;
                  if ($selfQuestion && $selfQuestion['self_total_points'] > 0) {
                    $selfPercentage = round(($selfQuestion['self_total_ratings'] / $selfQuestion['self_total_points']) * 100);
                  }

                  // Get family data
                  $famQuestion = collect($family['questions'])->firstWhere('question_text', $text);
                  $famPercentage = null;
                  if ($famQuestion && 
                      isset($famQuestion['others_total_points']) && 
                      isset($famQuestion['others_total_ratings']) && 
                      $famQuestion['others_total_points'] > 0) {
                    $famPercentage = round(($famQuestion['others_total_ratings'] / $famQuestion['others_total_points']) * 100);
                  }

                  // Get friend data
                  $frndQuestion = collect($friend['questions'])->firstWhere('question_text', $text);
                  $frndPercentage = null;
                  if ($frndQuestion && 
                      isset($frndQuestion['others_total_points']) && 
                      isset($frndQuestion['others_total_ratings']) && 
                      $frndQuestion['others_total_points'] > 0) {
                    $frndPercentage = round(($frndQuestion['others_total_ratings'] / $frndQuestion['others_total_points']) * 100);
                  }

                  // Calculate average for overall - only include valid percentages
                  $validPercentages = array_filter([$selfPercentage, $famPercentage, $frndPercentage], function($val) { 
                    return $val !== null; 
                  });
                  $avgPercentage = count($validPercentages) > 0 ? round(array_sum($validPercentages) / count($validPercentages)) : null;
                @endphp

                <tr>
                  <td>{{ $text ?: 'Question ' . $question['question_id'] }}</td>
                  <td>{{ $avgPercentage ? $getStatus($avgPercentage) . ', ' . $avgPercentage . '%' : '-' }}</td>
                  <td>{{ $format($selfPercentage) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
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
