<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use App\Models\Survey;
use App\Models\UsersSurveysRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authUserId = auth()->user()->id;

        // Retrieve all individual surveys (applies_to containing 'Individual')
        $individualSurveys = Survey::whereJsonContains('applies_to', 'Individual')->get();

        // Get user's answers (rates)
        $surveyRates = Survey::whereHas('usersSurveysRates', function ($query) use ($authUserId) {
                $query->where('users_id', $authUserId)
                    ->where('evaluatee_id', $authUserId)
                    ->whereNull('group_id');
            })
            ->with([
                'usersSurveysRates' => function ($q) use ($authUserId) {
                    $q->where('users_id', $authUserId)
                    ->where('evaluatee_id', $authUserId)
                    ->whereNull('group_id')
                    ->with('option'); // Load the selected option to get its points
                }
            ])
            ->get();

        // Sum points per survey and per type
        $surveyPoints = [];
        $typePoints = [
            'SELF' => [],
            'COMPETENCE' => [],
            'AUTONOMY' => [],
            'RELATEDNESS' => [],
        ];
        // Get type IDs for each type name
        $typeMap = \App\Models\Type::whereIn('name', ['SELF', 'COMPETENCE', 'AUTONOMY', 'RELATEDNESS'])->pluck('id', 'name');
        foreach ($surveyRates as $survey) {
            $total = 0;
            $typeTotals = [
                'SELF' => 0,
                'COMPETENCE' => 0,
                'AUTONOMY' => 0,
                'RELATEDNESS' => 0,
            ];
            foreach ($survey->usersSurveysRates as $rate) {
                $point = optional($rate->option)->point ?? 0;
                $total += $point;
                $questionTypeId = optional($rate->question)->type_id;
                foreach ($typeMap as $typeName => $typeId) {
                    if ($questionTypeId == $typeId) {
                        $typeTotals[$typeName] += $point;
                    }
                }
            }
            $surveyPoints[$survey->id] = $total;
            foreach ($typeTotals as $typeName => $val) {
                $typePoints[$typeName][$survey->id] = $val;
            }
        }

        // dd($surveyPoints);
        foreach ($individualSurveys as $survey) {
            // Check if the survey is already assigned to the authenticated user
            $isAssigned = DB::table('users_surveys')
                ->where('user_id', $authUserId)
                ->where('survey_id', $survey->id)
                ->exists();

            // If not assigned, assign it to the user
            if (!$isAssigned) {
                DB::table('users_surveys')->insert([
                    'user_id' => $authUserId,
                    'survey_id' => $survey->id,
                    'is_completed' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        // dd($surveyPoints, $typePoints);
  $allGroupSurveyResults = getAllGroupsCombinedTypeReportsCombinedByGroupType();
    $allreport = allreport();
    $surveytypequestion = getAllSelfAwarenessQuestionsFlatByGroupType();
       return view('dashboard', compact(
    'surveyPoints',
    'typePoints',
    'allGroupSurveyResults',
    'allreport',
    'surveytypequestion'
));

    }




}
