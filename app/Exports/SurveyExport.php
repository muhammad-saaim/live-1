<?php

namespace App\Exports;

use App\Models\UsersSurveysRate;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class SurveyExport implements FromView, WithEvents
{
    protected $user;
    protected $survey_id;

    public function __construct($user, $survey_id)
    {
        $this->user = $user;
        $this->survey_id = $survey_id;
    }

    public function view(): View
    {
        $UserSurveys = UsersSurveysRate::select([
            'users_surveys_rates.*',
            'users.name as user_name',
            'users.gender as user_gender',
            'surveys.title as survey_title',
            'questions.question as question_text',
            'questions.reverse_score',
            'question_options.name as option_name',
            'survey_models.title as survey_model_title',
            'groups.name as group_name',
            'types.name as type_name'
        ])
        ->join('users', 'users_surveys_rates.users_id', '=', 'users.id')
        ->join('surveys', 'users_surveys_rates.survey_id', '=', 'surveys.id')
        ->join('questions', 'users_surveys_rates.question_id', '=', 'questions.id')
        ->join('question_options', 'users_surveys_rates.options_id', '=', 'question_options.id')
        ->join('survey_models', 'surveys.model_id', '=', 'survey_models.id')
        ->join('groups', 'users_surveys_rates.group_id', '=', 'groups.id')
        ->leftJoin('types', 'questions.type_id', '=', 'types.id')
        ->when($this->survey_id, function ($query) {
            $query->where('users_surveys_rates.survey_id', $this->survey_id);
        })
        ->get();

        return view('reports.export', [
            'UserSurveys' => $UserSurveys
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestColumn = $sheet->getHighestColumn();
                $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

                // Apply rotation to all columns at once
                $range = 'A2:' . $highestColumn . '2';
                $sheet->getStyle($range)->getAlignment()->setTextRotation(90);
            },
        ];
    }
}
