<?php

namespace App\Exports;

use App\Models\UsersSurveysRate;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SurveyExport implements FromView
{
    protected $user;
    protected $survey_id;

    public function __construct($user, $survey_id )
    {
        $this->user = $user;
        $this->survey_id = $survey_id;
    }
    

    public function view(): View
    { 
           if($this->survey_id) {
            $UserSurveys = UsersSurveysRate::with(['user', 'survey', 'question', 'option', 'surveyModel','types','group'])
                ->where('survey_id', $this->survey_id)
                ->get()
                ->filter(function ($rate) {
                    // Remove if any related model is missing
                    return $rate->survey && $rate->user && $rate->question && $rate->option;
                })
            
                ->values();
                 return view('reports.export', [
            'UserSurveys' => $UserSurveys
        ]);
             } // optional: reset keys nicely
        $UserSurveys = UsersSurveysRate::with(['user', 'survey', 'question', 'option', 'surveyModel','types','group'])
            ->get()
            ->filter(function ($rate) {
                // Remove if any related model is missing
                return $rate->survey && $rate->user && $rate->question && $rate->option;
            })
            ->values(); // optional: reset keys nicely

        return view('reports.export', [
            'UserSurveys' => $UserSurveys
        ]);
    }

}

