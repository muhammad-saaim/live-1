<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Models\UsersSurveysRate;

class UserSurveysExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents
{
    public function collection()
    {
        return UsersSurveysRate::with(['group', 'survey', 'surveyModel', 'question', 'types', 'option'])->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Group Id',
            'Group Name',
            'Evaluator',
            'Evaluatee',
            'Survey',
            'Survey Type',
            'Question',
            'Question Type',
            'Selected Option',
            'Applies To',
            'Score Type',
            'Type Names',
            'Total Points by Type'
        ];
    }

    public function map($entry): array
    {
        return [
            $entry->id,
            $entry->group_id ?? 'N/A',
            $entry->group->name ?? 'N/A',
            $entry->users_id ?? 'N/A',
            $entry->evaluatee_id ?? 'N/A',
            $entry->survey->title ?? 'N/A',
            $entry->surveyModel->title ?? 'N/A',
            $entry->question->question ?? 'N/A',
            $entry->types->name ?? 'N/A',
            $entry->option->name ?? 'N/A',
            is_array($entry->survey->applies_to) ? implode(', ', $entry->survey->applies_to) : ($entry->survey->applies_to ?? 'N/A'),
            $entry->question->reverse_score === 0 ? 'Normal' : ($entry->question->reverse_score === 1 ? 'Reverse' : 'N/A'),
            $entry->types->name,
            $entry->option->points ?? 0
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFFF00']
                ]
            ],
            'A1:N1' => [
                'borders' => [
                    'outline' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function(\Maatwebsite\Excel\Events\AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:N1');
                $event->sheet->getDelegate()->getStyle('A1:N1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
        ];
    }
}