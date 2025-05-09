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
            // ->where('users_id', $this->user->id)
            ->get();
// dd($UserSurveys);
        return view('reports.export', [
            'UserSurveys' => $UserSurveys
        ]);
    }
}

