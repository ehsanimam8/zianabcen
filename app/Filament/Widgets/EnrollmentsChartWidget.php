<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\SIS\Program;
use App\Models\SIS\Enrollment;

class EnrollmentsChartWidget extends ChartWidget
{
    protected ?string $heading = 'Enrollments By Program';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'half';

    protected function getData(): array
    {
        $programs = Program::withCount([
            'enrollments' => function ($query) {
                $query->where('status', 'Enrolled');
            }
        ])
        ->orderBy('enrollments_count', 'desc')
        ->take(5)
        ->get();

        $labels = [];
        $data = [];
        
        foreach ($programs as $program) {
            $labels[] = str()->limit($program->name, 20); // truncate long titles
            $data[] = $program->enrollments_count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Active Enrollments',
                    'data' => $data,
                    'backgroundColor' => '#171717', // primary color matching theme
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
