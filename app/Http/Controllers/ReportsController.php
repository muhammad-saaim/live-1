<?php

namespace App\Http\Controllers;

use App\Exports\SurveyExport;
use App\Models\UsersSurveysRate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
    
        // Check if user is admin using Spatie's hasRole method
        if ($user->hasRole('admin')) {
            // Admin: Get all rating records, eager load related survey and option points
            $userSurveyRates = \App\Models\UsersSurveysRate::with('survey')
                ->join('question_options', 'users_surveys_rates.options_id', '=', 'question_options.id')
                ->select('users_surveys_rates.*', 'question_options.point')
                ->get();
        } else {
            // Non-admin: Get only the user's rating records
            $userSurveyRates = $user->usersSurveysRates()
                ->with('survey')
                ->join('question_options', 'users_surveys_rates.options_id', '=', 'question_options.id')
                ->select('users_surveys_rates.*', 'question_options.point')
                ->get();
        }
    
        $surveyAverages = [];
    
        // Group by survey_id to calculate average for each survey
        foreach ($userSurveyRates->groupBy('survey_id') as $surveyId => $rates) {
            $average = $rates->avg('point');
            $surveyAverages[$surveyId] = $average;
        }
    
        // Collect distinct surveys (so you're not looping over raw rating rows)
        $distinctSurveys = $userSurveyRates->pluck('survey')->filter()->unique('id')->values();
     $allGroupSurveyResults = getAllGroupsCombinedTypeReportsCombinedByGroupType();
     $allreport=allreport();

     $surveytypequestion=getAllSelfAwarenessQuestionsFlatByGroupType();

             // Step 1: Extract labels from individual questions (assume all groups have same questions)
    $labels = collect($surveytypequestion['individual']['questions'] ?? [])
    ->pluck('question_text')
    ->toArray();

// If no individual questions exist, try family or friend
if (empty($labels)) {
    $labels = collect($surveytypequestion['family']['questions'] ?? [])
        ->pluck('question_text')
        ->toArray();
}

if (empty($labels)) {
    $labels = collect($surveytypequestion['friend']['questions'] ?? [])
        ->pluck('question_text')
        ->toArray();
}

    // Step 2: Build datasets
    $datasets = [];
// Overall Rating (Self + Others from all groups)
// Step 1: Get all groups
$groups = ['individual', 'family', 'friend'];

// Step 2: Find the group with the most questions
$maxGroup = null;
$maxCount = 0;

foreach ($groups as $group) {
    $count = count($surveytypequestion[$group]['questions'] ?? []);
    if ($count > $maxCount) {
        $maxCount = $count;
        $maxGroup = $group;
    }
}

// If no group has questions, set default empty label
$labels = [];
if ($maxGroup) {
    $labels = collect($surveytypequestion[$maxGroup]['questions'])
        ->pluck('question_text')
        ->toArray();
}

if (empty($labels)) {
    $labels = ['No Questions Available'];
}

// Step 3: Build Overall Dataset using $maxGroup indexes
$datasets[] = [
    'label' => 'Overall',
    'data' => collect($surveytypequestion[$maxGroup]['questions'] ?? [])->map(function ($q, $index) use ($surveytypequestion, $groups) {
        $getValue = function ($type, $index, $field) use ($surveytypequestion) {
            return isset($surveytypequestion[$type]['questions'][$index][$field])
                ? $surveytypequestion[$type]['questions'][$index][$field]
                : 0;
        };

        $totalRatings = 0;
        $totalPoints = 0;

        // Sum ratings & points for all groups at the same index
        foreach ($groups as $group) {
            $totalRatings += $getValue($group, $index, 'self_total_ratings');
            $totalRatings += $getValue($group, $index, 'others_total_ratings');
            $totalPoints += $getValue($group, $index, 'self_total_points');
            $totalPoints += $getValue($group, $index, 'others_total_points');
        }

        return $totalRatings > 0
            ? round(($totalPoints / ($totalRatings * 5)) * 100, 0)
            : 0;
    })->toArray(),
    'backgroundColor' => '#9b59b6'
];


     

    // Individual (Self-Evaluation)
    $datasets[] = [
        'label' => 'Self-Evaluation',
        'data' => collect($surveytypequestion['individual']['questions'])->map(function ($q) {
            return $q['self_total_ratings'] > 0
                ? round(($q['self_total_points'] / ($q['self_total_ratings'] * 5)) * 100, 0)
                : 0;
        })->toArray(),
        'backgroundColor' => '#1abc9c'
    ];

    // Family (Others + Self)
$datasets[] = [
    'label' => 'Family Members',
    'data' => collect($surveytypequestion['family']['questions'])->map(function ($q) {
        $totalPoints = ($q['others_total_points'] ?? 0) + ($q['self_total_points'] ?? 0);
        $totalRatings = ($q['others_total_ratings'] ?? 0) + ($q['self_total_ratings'] ?? 0);

        return $totalRatings > 0
            ? round(($totalPoints / ($totalRatings * 5)) * 100, 2)
            : 0;
    })->toArray(),
    'backgroundColor' => '#f39c12'
];

// Friends (Others + Self)
$datasets[] = [
    'label' => 'Friends',
    'data' => collect($surveytypequestion['friend']['questions'])->map(function ($q) {
        $totalPoints = ($q['others_total_points'] ?? 0) + ($q['self_total_points'] ?? 0);
        $totalRatings = ($q['others_total_ratings'] ?? 0) + ($q['self_total_ratings'] ?? 0);

        return $totalRatings > 0
            ? round(($totalPoints / ($totalRatings * 5)) * 100, 2)
            : 0;
    })->toArray(),
    'backgroundColor' => '#e74c3c'
];
        // dd($distinctSurveys,$surveyAverages,$allGroupSurveyResults,$allreport,$surveytypequestion,$labels,$datasets);

        return view('reports.reports-index', [
            'UserSurveys' => $distinctSurveys,
            'surveyAverages' => $surveyAverages,
            'allGroupSurveyResults' => $allGroupSurveyResults,
            'allreport'=>$allreport,
            'surveytypequestion'=>$surveytypequestion,
             'labels' => $labels,
        'datasets' => $datasets
        ]);
    }
    
    public function showChart()
{


    // Step 1: Extract labels from individual questions (assume all groups have same questions)
    $labels = collect($surveytypequestion['individual']['questions'])
                ->pluck('question_text')
                ->toArray();

    // Step 2: Build datasets
    $datasets = [];

    // Individual (Self-Evaluation)
    $datasets[] = [
        'label' => 'Self-Evaluation',
        'data' => collect($surveytypequestion['individual']['questions'])->map(function ($q) {
            return $q['self_total_ratings'] > 0
                ? round(($q['self_total_points'] / ($q['self_total_ratings'] * 5)) * 100, 2)
                : 0;
        })->toArray(),
        'backgroundColor' => '#1abc9c'
    ];

    // Family (Others)
    $datasets[] = [
        'label' => 'Family Members',
        'data' => collect($surveytypequestion['family']['questions'])->map(function ($q) {
            return $q['others_total_ratings'] > 0
                ? round(($q['others_total_points'] / ($q['others_total_ratings'] * 5)) * 100, 2)
                : 0;
        })->toArray(),
        'backgroundColor' => '#f39c12'
    ];

    // Friends (Others)
    $datasets[] = [
        'label' => 'Friends',
        'data' => collect($surveytypequestion['friend']['questions'])->map(function ($q) {
            return $q['others_total_ratings'] > 0
                ? round(($q['others_total_points'] / ($q['others_total_ratings'] * 5)) * 100, 2)
                : 0;
        })->toArray(),
        'backgroundColor' => '#e74c3c'
    ];

    return view('reports.reports-index', [
       
    ]);
}


    public function downloadPdf(Request $request)
    {
        $type = $request->get('type');

        // Prepare the data for the views
        $data = [];

        if ($type === 'bar') {
            // Bar report data
            $data['bars'] = [
                ['label' => 'Completed Tasks', 'value' => 70],
                ['label' => 'In Progress Tasks', 'value' => 40],
                ['label' => 'Pending Tasks', 'value' => 90],
                // Add more bars as needed
            ];

            $view = view('reports.bar', $data)->render();
        } else {
            // Text report data
            $data = [
                'title' => 'Mentor Değerlendirme Raporu',
                'summary' => 'Bay/Ms. Smith, iş dünyasında deneyimli ve yetenekli bir profesyoneldir...',
                'performance' => [
                    ['label' => 'Bilgi ve Deneyim', 'content' => 'Smith\'in iş dünyasındaki bilgi ve deneyimi etkileyicidir...'],
                    ['label' => 'İletişim Becerileri', 'content' => 'İletişimde son derece başarılı olan Smith...'],
                    // Add more points as needed
                ],
                'suggestions' => [
                    'Smith’in mentorluk hizmetlerini daha geniş kitlelere ulaştırması için dijital platformları kullanması önerilebilir...',
                    'Smith’in profesyonel gelişimine devam etmesi önemlidir...',
                ],
                'conclusion' => 'Bay/Ms. Smith, kusursuz mentorluk becerileri ve geniş iş deneyimiyle öne çıkan biridir...',
            ];

            $view = view('reports.text', $data)->render();
        }

        // Generate the PDF
        $pdf = Pdf::loadHTML($view);
        return $pdf->download('report.pdf');
    }

   public function exportSurveyExcel(Request $request)
{   
   $request->validate([
        'survey_id' => 'required|integer',
        'start_date' => 'required|date',
    ]);

    $survey_id = $request->survey_id;
    $startDate = $request->start_date;
    $endDate = now()->format('Y-m-d'); // today's date
      
    $user = Auth::user();

    return Excel::download(
        new SurveyExport($user,  $survey_id,$startDate, $endDate),
        'survey-report.xlsx'
    );
}

}