<?php

namespace App\Exports;

use App\Models\User;
use App\Models\UserRelative;
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
        $UserSurveys = UsersSurveysRate::with(['user', 'survey', 'question', 'option', 'surveyModel','types','group','userRelation','evaluateeRelation'])
            ->when($this->survey_id, function ($query) {
                $query->where('survey_id', $this->survey_id);
            })
            ->get()
            ->filter(function ($rate) {
                return $rate->survey && $rate->user && $rate->question && $rate->option;
            })
            ->values();

        return view('reports.export', [
            'UserSurveys' => $UserSurveys
        ]);
    }
    


  public function registerEvents(): array
{
    return [
        AfterSheet::class => function (AfterSheet $event) {
            $sheet = $event->sheet->getDelegate();

            // Get highest column (e.g., 'AD', 'BA', etc.)
            $highestColumn = $sheet->getHighestColumn();
            $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

            // Loop through all columns from 1 to highest
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $columnLetter = Coordinate::stringFromColumnIndex($col);

                // Apply 90-degree rotation to both row 1 and row 2
                // $sheet->getStyle($columnLetter . '1')->getAlignment()->setTextRotation(90);
                $sheet->getStyle($columnLetter . '2')->getAlignment()->setTextRotation(90);
            }
        },
    ];
}

}
