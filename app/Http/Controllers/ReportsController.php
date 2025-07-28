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
    
        $allGroupSurveyResults = getAllUserSurveyReports();
        //  dd($allGroupSurveyResults);

        return view('reports.reports-index', [
            'UserSurveys' => $distinctSurveys,
            'surveyAverages' => $surveyAverages
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
        // Validate the request to ensure 'id' is provided
    $survey_id = $request->input('id');
        $user = Auth::user();
        return Excel::download(new SurveyExport($user,$survey_id), 'survey-report.xlsx');
    

}
}