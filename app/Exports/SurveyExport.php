<?php

namespace App\Exports;

use App\Models\UsersSurveysRate;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SurveyExport implements FromView
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function view(): View
    {
        $UserSurveys = UsersSurveysRate::with(['user', 'survey', 'question', 'option'])
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

