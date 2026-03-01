<?php

namespace App\Filament\Widgets;

use App\Models\SIS\Course;
use Filament\Widgets\ChartWidget;

class EnrollmentsChartWidget extends ChartWidget
{
    protected ?string $heading = 'Enrollments By Course';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'half';

    protected function getData(): array
    {
        $courses = Course::withCount([
            'enrollments' => fn ($query) => $query->where('status', 'Enrolled'),
        ])
            ->orderBy('enrollments_count', 'desc')
            ->take(5)
            ->get();

        $labels = [];
        $data   = [];

        foreach ($courses as $course) {
            $labels[] = str()->limit($course->name, 20);
            $data[]   = $course->enrollments_count;
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Active Enrollments',
                    'data'            => $data,
                    'backgroundColor' => '#171717',
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
